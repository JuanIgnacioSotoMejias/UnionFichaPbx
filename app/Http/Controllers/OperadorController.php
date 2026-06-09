<?php

namespace App\Http\Controllers;

use App\Models\Extension;
use App\Models\OperadorConfig;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class OperadorController extends Controller
{
    /**
     * Muestra la lista de operadores (Módulo de Personal)
     */
    public function index(): View
    {
        // Operadores sin ninguna extensión (Disponibles) — Con eager loading
        $operadoresDisponibles = OperadorConfig::with('extensiones')->doesntHave('extensiones')->orderBy('nombre_operador')->get();
        
        // Extensiones con sus operadores asignados
        $extensionesConOperadores = Extension::with(['operadores' => function ($q) {
            $q->orderBy('nombre_operador');
        }])->has('operadores')->orderBy('numero')->get();

        // Todas las extensiones para el select/modal de asignación
        $extensionesTotales = Extension::orderBy('numero')->get();

        return view('operadores.index', compact('operadoresDisponibles', 'extensionesConOperadores', 'extensionesTotales'));
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

        // Actualizar datos base del operador
        $operador->update([
            'grupo_horario' => $grupoHorario,
            'horario_turno' => $request->input('horario_turno'),
            'horario_comida' => $request->input('horario_comida'),
            'horario_descanso' => $request->input('horario_descanso'),
            'is_active'     => $request->input('is_active'),
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
}
