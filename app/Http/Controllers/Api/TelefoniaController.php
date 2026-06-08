<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HistorialAcceso;
use App\Models\LogApiReceptor;
use App\Models\OperadorConfig;
use App\Services\AmiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

/**
 * Controlador principal de la API receptora.
 *
 * Recibe eventos del sistema Ficha y los traduce en acciones
 * sobre la cola de llamadas de FreePBX via AMI.
 */
class TelefoniaController extends Controller
{
    public function __construct(private readonly AmiService $ami) {}

    // =========================================================================
    // POST /api/sesion
    // =========================================================================

    /**
     * Endpoint principal: recibe LOGIN / LOGOUT desde el sistema Ficha.
     *
     * Payload esperado:
     * {
     *   "usuario":  "jperez",      // campo 'usuario' de ficha_ven_911.usuarios
     *   "evento":   "LOGIN"|"LOGOUT"
     * }
     */
    public function sesion(Request $request): JsonResponse
    {
        // ── 1. Registrar payload crudo ─────────────────────────────────────
        $log = LogApiReceptor::create([
            'payload_recibido' => $request->all(),
            'codigo_respuesta' => 200,
            'mensaje_error'    => null,
        ]);

        // ── 2. Validar campos requeridos ───────────────────────────────────
        $validated = $request->validate([
            'usuario' => ['required', 'string', 'max:50'],
            'evento'  => ['required', Rule::in(['LOGIN', 'LOGOUT'])],
        ]);

        $fichaUsername = $validated['usuario'];
        $evento        = $validated['evento'];

        // ── 3. Buscar operador registrado ──────────────────────────────────
        $operador = OperadorConfig::findByFichaUsername($fichaUsername);

        if (!$operador) {
            return $this->errorResponse($log, 404,
                "Operador '{$fichaUsername}' no está registrado en el sistema receptor."
            );
        }

        // ── 4. Ejecutar acción AMI según evento ────────────────────────────
        try {
            if ($evento === 'LOGIN') {
                $resultado = $this->ami->addToQueue($operador->extension, $operador->queue_name);
                $operador->update(['is_active' => true]);

                HistorialAcceso::create([
                    'operador_config_id' => $operador->id,
                    'evento'             => 'LOGIN',
                    'origen_ip'          => $request->ip(),
                ]);

                Log::info("[Receptor] LOGIN: {$fichaUsername} | Ext: {$operador->extension} | Cola: {$operador->queue_name}");

            } else { // LOGOUT
                $resultado = $this->ami->removeFromQueue($operador->extension, $operador->queue_name);
                $operador->update(['is_active' => false]);

                HistorialAcceso::create([
                    'operador_config_id' => $operador->id,
                    'evento'             => 'LOGOUT',
                    'origen_ip'          => $request->ip(),
                ]);

                Log::info("[Receptor] LOGOUT: {$fichaUsername} | Ext: {$operador->extension} | Cola: {$operador->queue_name}");
            }

        } catch (\Throwable $e) {
            return $this->errorResponse($log, 500, 'Error al comunicarse con FreePBX: ' . $e->getMessage());
        }

        // ── 5. Respuesta exitosa ───────────────────────────────────────────
        $log->update(['codigo_respuesta' => 200]);

        return response()->json([
            'ok'        => true,
            'evento'    => $evento,
            'operador'  => $operador->nombre_operador,
            'extension' => $operador->extension,
            'cola'      => $operador->queue_name,
            'ami_status' => $resultado['status'],
            'dry_run'   => $resultado['dry_run'],
        ]);
    }

    // =========================================================================
    // GET /api/status
    // =========================================================================

    /**
     * Health-check: estado del servicio receptor y conexión AMI.
     */
    public function status(): JsonResponse
    {
        $operadoresActivos = OperadorConfig::where('is_active', true)->count();
        $totalOperadores   = OperadorConfig::count();
        $dryRun            = config('ami.dry_run', false);

        // Verificar conectividad AMI solo si no está en dry_run
        $amiOk = false;
        $amiMsg = 'Modo DRY_RUN activo';

        if (!$dryRun) {
            try {
                $socket = @fsockopen(
                    config('ami.host'),
                    config('ami.port'),
                    $errno,
                    $errstr,
                    (int) config('ami.connect_timeout', 3)
                );
                if ($socket) {
                    fclose($socket);
                    $amiOk  = true;
                    $amiMsg = 'Conectado';
                } else {
                    $amiMsg = "No disponible: {$errstr} ({$errno})";
                }
            } catch (\Throwable $e) {
                $amiMsg = 'Error: ' . $e->getMessage();
            }
        }

        return response()->json([
            'ok'      => true,
            'servicio' => 'PBX Receptor',
            'version'  => '1.0.0',
            'ami' => [
                'conectado' => $amiOk,
                'dry_run'   => $dryRun,
                'mensaje'   => $amiMsg,
                'host'      => config('ami.host') . ':' . config('ami.port'),
            ],
            'operadores' => [
                'activos' => $operadoresActivos,
                'total'   => $totalOperadores,
            ],
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    // =========================================================================
    // GET /api/operadores
    // =========================================================================

    /**
     * Lista todos los operadores registrados con su estado actual.
     */
    public function listarOperadores(): JsonResponse
    {
        $operadores = OperadorConfig::orderBy('nombre_operador')
            ->get(['id', 'ficha_username', 'nombre_operador', 'extension', 'queue_name', 'is_active', 'updated_at']);

        return response()->json([
            'ok'   => true,
            'data' => $operadores,
        ]);
    }

    // =========================================================================
    // Helpers privados
    // =========================================================================

    private function errorResponse(LogApiReceptor $log, int $code, string $mensaje): JsonResponse
    {
        $log->update([
            'codigo_respuesta' => $code,
            'mensaje_error'    => $mensaje,
        ]);

        Log::warning("[Receptor] Error {$code}: {$mensaje}");

        return response()->json([
            'ok'      => false,
            'message' => $mensaje,
        ], $code);
    }
}
