<?php

namespace App\Http\Controllers;

use App\Models\Extension;
use App\Models\OperadorConfig;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OperadorController extends Controller
{
    /**
     * Muestra la lista de operadores (Módulo de Personal)
     */
    public function index(): View
    {
        // Operadores sin ninguna extensión (Disponibles) — Con eager loading y filtrando por activos
        $operadoresDisponibles = OperadorConfig::with('extensiones')
            ->where('is_active', true)
            ->doesntHave('extensiones')
            ->orderBy('nombre_operador')
            ->get();
        
        // Extensiones con sus operadores asignados (filtrando por activos)
        $extensionesConOperadores = Extension::with(['operadores' => function ($q) {
            $q->where('operadores_config.is_active', true)->orderBy('nombre_operador');
        }])->whereHas('operadores', function ($q) {
            $q->where('operadores_config.is_active', true);
        })->orderBy('numero')->get();

        // Todas las extensiones para el select/modal de asignación
        $extensionesTotales = Extension::orderBy('numero')->get();

        // Operadores deshabilitados
        $operadoresDeshabilitados = OperadorConfig::with('extensiones')
            ->where('is_active', false)
            ->orderBy('nombre_operador')
            ->get();

        return view('operadores.index', compact('operadoresDisponibles', 'extensionesConOperadores', 'extensionesTotales', 'operadoresDeshabilitados'));
    }

    /**
     * Actualiza la extensión y/o el grupo horario de un operador
     */
    public function update(\App\Http\Requests\UpdateOperadorRequest $request, OperadorConfig $operador): RedirectResponse
    {
        Gate::authorize('manage-system');
        $extensionesRequest = $request->input('extensiones', []);
        $grupoHorario = $request->input('grupo_horario');

        // Validar límite estricto de 12 operadores por extensión
        foreach ($extensionesRequest as $extNum) {
            $ext = Extension::where('numero', $extNum)->first();
            if ($ext) {
                $currentCount = $ext->operadores()->where('operadores_config.id', '!=', $operador->id)->count();
                if ($currentCount >= 12) {
                    return back()->withErrors(['extensiones' => "La extensión {$extNum} ya tiene el límite máximo de 12 operadores asignados."]);
                }
            }
        }

        // Obtener extensiones previas antes del sync para recalcular
        $previousExtIds = $operador->extensiones()->pluck('extensions.id')->toArray();

        $extIds = Extension::whereIn('numero', $extensionesRequest)->pluck('id')->toArray();
        $operador->extensiones()->sync($extIds);

        // Recalcular estado solo de las extensiones afectadas (previas + nuevas)
        $affectedIds = array_unique(array_merge($previousExtIds, $extIds));
        if (!empty($affectedIds)) {
            Extension::whereIn('id', $affectedIds)->each(function ($e) {
                $estado = $e->operadores()->count() > 0 ? 'en_uso' : 'libre';
                if ($e->estado !== $estado) {
                    $e->update(['estado' => $estado]);
                }
            });
        }

        // Copiar la primera extensión asignada a la columna base para facilitar consultas directas
        $primeraExtension = Extension::whereIn('id', $extIds)->first();
        $extensionBase = $primeraExtension ? $primeraExtension->numero : '0000';

        // Actualizar datos base del operador
        $operador->update([
            'extension'      => $extensionBase,
            'grupo_horario'  => $grupoHorario,
            'horario_turno'  => $request->input('horario_turno'),
            'horario_comida' => $request->input('horario_comida'),
            'horario_descanso' => $request->input('horario_descanso'),
            'is_active'      => $request->input('is_active'),
        ]);

        return redirect()->route('operadores.index')->with('success', 'Operador actualizado exitosamente.');
    }

    /**
     * Activar / desactivar manualmente (sin pasar por AMI).
     * Útil para corrección administrativa.
     */
    public function toggleActivo(OperadorConfig $operador): RedirectResponse
    {
        Gate::authorize('manage-system');
        $operador->update(['is_active' => !$operador->is_active]);
        $estado = $operador->is_active ? 'activado' : 'desactivado';

        return back()->with('success', "Operador '{$operador->nombre_operador}' {$estado} manualmente.");
    }

    /**
     * Sincroniza de forma masiva los operadores desde la API de la Ficha.
     */
    public function sincronizarDesdeFicha(Request $request): RedirectResponse
    {
        Gate::authorize('manage-system');
        
        $config = config('ficha_api');
        $url = rtrim($config['base_url'], '/') . '/index.php?url=fichaApi/operadores';
        
        try {
            $response = Http::timeout($config['timeout'])
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $config['token'],
                    'Accept' => 'application/json'
                ])
                ->get($url);
                
            if (!$response->successful()) {
                return back()->withErrors(['sincronizar' => 'Error al consultar la Ficha (HTTP ' . $response->status() . ')']);
            }
            
            $result = $response->json();
            if (!isset($result['success']) || !$result['success'] || !isset($result['data'])) {
                return back()->withErrors(['sincronizar' => 'La API de la Ficha no retornó datos válidos.']);
            }
            
            $operadores = $result['data'];
            $creados = 0;
            $actualizados = 0;
            
            foreach ($operadores as $op) {
                $fichaUsername = $op['usuario'];
                $nombre = $op['nombre_completo'];
                $cedula = $op['cedula'] ?? null;
                
                // 1. Gestionar OperadorConfig
                $operador = OperadorConfig::where('ficha_username', $fichaUsername)->first();
                if (!$operador) {
                    $operador = OperadorConfig::create([
                        'ficha_username'  => $fichaUsername,
                        'nombre_operador' => $nombre,
                        'extension'       => '0000',
                        'queue_name'      => 'ven911',
                        'is_active'       => false,
                    ]);
                    $creados++;
                } else {
                    $operador->update([
                        'nombre_operador' => $nombre
                    ]);
                    $actualizados++;
                }
                
                // 2. Gestionar User
                $email = $fichaUsername . '@ficha.local';
                $user = User::where('email', $email)->first();
                if (!$user) {
                    User::create([
                        'name'      => $nombre,
                        'email'     => $email,
                        'cedula'    => $cedula,
                        'password'  => Hash::make(Str::random(32)),
                        'role'      => User::ROLE_USER,
                        'is_active' => true,
                    ]);
                } else {
                    $user->update([
                        'name' => $nombre,
                        'cedula' => $cedula,
                    ]);
                }
            }
            
            return back()->with('success', "Sincronización finalizada. Operadores procesados: {$creados} nuevos, {$actualizados} actualizados.");
            
        } catch (\Exception $e) {
            Log::error('[PBX] Error sincronizando desde Ficha: ' . $e->getMessage());
            return back()->withErrors(['sincronizar' => 'Error de conexión: ' . $e->getMessage()]);
        }
    }
}
