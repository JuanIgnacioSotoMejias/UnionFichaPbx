<?php

namespace App\Http\Controllers;

use App\Models\Extension;
use App\Models\OperadorConfig;
use App\Services\AmiService;
use App\Services\FreePbxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExtensionController extends Controller
{
    public function __construct(
        private readonly FreePbxService $freePbx,
        private readonly AmiService $ami
    ) {
    }

    /**
     * Vista principal: lista todas las extensiones con sus operadores asignados
     * y el estado en tiempo real vía AMI.
     */
    public function index()
    {
        // Paginamos separando por prefijo
        $extensionsOperadores = Extension::with('operador')
            ->where('numero', 'like', '8%')
            ->orderBy('numero')
            ->paginate(5, ['*'], 'page_op');

        $extensionsInternos = Extension::with('operador')
            ->where('numero', 'like', '9%')
            ->orderBy('numero')
            ->paginate(5, ['*'], 'page_int');

        // Consultar estado real de cada extensión vía AMI
        $numerosOp = $extensionsOperadores->pluck('numero')->toArray();
        $numerosInt = $extensionsInternos->pluck('numero')->toArray();
        $numeros = array_merge($numerosOp, $numerosInt);
        $estadosAmi = [];
        $amiError = null;

        try {
            $estadosAmi = $this->ami->getExtensionsStatuses($numeros);
        } catch (\Throwable $e) {
            Log::warning('[Extensions] No se pudieron obtener estados AMI: ' . $e->getMessage());
            $amiError = 'Asterisk temporalmente inalcanzable';
            $estadosAmi = array_fill_keys($numeros, 'OFFLINE');
        }

        // Operadores sin extensión asignada (para el select de asignación manual)
        $operadoresSinExtension = OperadorConfig::whereDoesntHave('extensionAsignada')
            ->orderBy('nombre_operador')
            ->get();

        $totalExtensions = Extension::all(); // Solo para las estadísticas visuales

        return view('extensions.index', compact(
            'extensionsOperadores', 
            'extensionsInternos', 
            'estadosAmi', 
            'operadoresSinExtension',
            'totalExtensions',
            'amiError'
        ));
    }

    /**
     * Sincroniza las extensiones desde FreePBX vía GraphQL.
     */
    public function sincronizar()
    {
        try {
            $extensionesRemote = $this->freePbx->fetchAllExtensionsDetailed();

            if (empty($extensionesRemote)) {
                return redirect()->route('extensions.index')
                    ->with('warning', 'No se obtuvieron extensiones de FreePBX. Verifica la conexión.');
            }

            $creadas = 0;
            $actualizadas = 0;

            foreach ($extensionesRemote as $ext) {
                $existing = Extension::where('numero', $ext['extension'])->first();

                if ($existing) {
                    $existing->update([
                        'nombre_freepbx'   => $ext['name'],
                        'tipo_tecnologia'  => $ext['tech'],
                        'sincronizado_at'  => now(),
                    ]);
                    $actualizadas++;
                } else {
                    Extension::create([
                        'numero'           => $ext['extension'],
                        'descripcion'      => $ext['name'],
                        'nombre_freepbx'   => $ext['name'],
                        'tipo_tecnologia'  => $ext['tech'],
                        'estado'           => 'libre',
                        'sincronizado_at'  => now(),
                    ]);
                    $creadas++;
                }
            }

            $msg = "Sincronización completada: {$creadas} nuevas, {$actualizadas} actualizadas de " . count($extensionesRemote) . " totales.";
            Log::info("[Extensions] {$msg}");

            return redirect()->route('extensions.index')->with('success', $msg);
        } catch (\Throwable $e) {
            Log::error('[Extensions] Error en sincronización: ' . $e->getMessage());
            return redirect()->route('extensions.index')
                ->with('error', 'Error al sincronizar: ' . $e->getMessage());
        }
    }

    /**
     * Prueba dinámica por Socket a la extensión seleccionada.
     */
    public function testExtension($id, AmiService $amiService)
    {
        $extension = Extension::findOrFail($id);

        if (env('AMI_DRY_RUN', false)) {
            return response()->json([
                'success' => true,
                'message' => "Prueba simulada [DRY RUN] para extensión {$extension->numero}. Señal recibida."
            ]);
        }

        try {
            $status = $amiService->getExtensionsStatuses([$extension->numero]);
            
            if (($status[$extension->numero] ?? 'offline') !== 'offline') {
                return response()->json(['success' => true, 'message' => "¡Dispositivo {$extension->numero} en línea y respondiendo ping AMI!"]);
            }
            
            return response()->json(['success' => false, 'message' => "El dispositivo {$extension->numero} no responde en la red interna."]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => "Error de red al contactar con el socket: " . $e->getMessage()]);
        }
    }

    /**
     * Libera una extensión.
     */
    public function liberar(Extension $extension)
    {
        $nombreOp = $extension->operador?->nombre_operador ?? 'N/A';
        $extension->markAsFree();

        Log::info("[Extensions] Liberada: Ext {$extension->numero} (antes: {$nombreOp})");

        return redirect()->route('extensions.index')
            ->with('success', "Extensión {$extension->numero} liberada exitosamente.");
    }

    /**
     * Formulario para crear extensión manual.
     */
    public function create()
    {
        return view('extensions.create');
    }

    /**
     * Guarda una extensión creada manualmente.
     */
    public function store(\App\Http\Requests\StoreExtensionRequest $request)
    {
        Extension::create([
            'numero'          => $request->numero,
            'descripcion'     => $request->descripcion,
            'nombre_freepbx'  => $request->descripcion,
            'tipo_tecnologia' => 'pjsip',
            'estado'          => 'libre',
            'grupo_horario'   => $request->grupo_horario,
        ]);

        return redirect()->route('extensions.index')->with('success', 'Extensión agregada exitosamente.');
    }

    /**
     * Formulario de edición de extensión.
     */
    public function edit(Extension $extension)
    {
        return view('extensions.edit', compact('extension'));
    }

    /**
     * Actualizar extensión.
     */
    public function update(\App\Http\Requests\UpdateExtensionRequest $request, Extension $extension)
    {
        $extension->update($request->only(['numero', 'descripcion', 'estado', 'grupo_horario']));

        // Si se marca manualmente como libre, desvincular operador
        if ($request->estado === 'libre') {
            $extension->update(['operador_config_id' => null]);
        }

        return redirect()->route('extensions.index')->with('success', 'Extensión actualizada exitosamente.');
    }

    /**
     * Eliminar extensión.
     */
    public function destroy(Extension $extension)
    {
        $extension->delete();
        return redirect()->route('extensions.index')->with('success', 'Extensión eliminada.');
    }
}