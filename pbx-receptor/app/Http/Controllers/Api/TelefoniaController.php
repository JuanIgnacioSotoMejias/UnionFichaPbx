<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SesionRequest;
use App\Models\HistorialAcceso;
use App\Models\LogApiReceptor;
use App\Models\OperadorConfig;
use App\Models\OperadorSession;
use App\Services\AmiService;
use App\Services\IntelligenceService;
use App\Models\Extension;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Controlador principal de la API receptora.
 * Recibe eventos del sistema Ficha y los traduce en acciones sobre la PBX.
 */
class TelefoniaController extends Controller
{
    public function __construct(
        private readonly AmiService $ami,
        private readonly \App\Services\TelefoniaService $telefoniaService
    ) {}

    /**
     * Endpoint principal: recibe LOGIN / LOGOUT desde el sistema Ficha.
     */
    public function sesion(SesionRequest $request): JsonResponse
    {
        $payloadRaw = $request->all();
        
        Log::info('------------------------------------------------------------');
        Log::info('[DEBUG Phase 1] Petición detectada en /api/sesion');
        Log::info('[DEBUG Phase 1] Payload completo:', $payloadRaw);
        Log::info('------------------------------------------------------------');

        $log = LogApiReceptor::create([
            'payload_recibido' => $payloadRaw,
            'codigo_respuesta' => 200,
            'mensaje_error'    => null,
        ]);

        $validated = $request->validated();
        $evento    = $validated['evento'];
        
        $operador = $this->telefoniaService->gestionarOperador($validated['usuario'], $request);

        $resultado = [
            'success' => false,
            'message' => 'Instancia no procesada',
            'status'  => 'Desconectado',
            'dry_run' => (bool) config('ami.dry_run', false)
        ];

        try {
            if ($evento === 'LOGIN') {
                $resultado = $this->telefoniaService->procesarLogin($operador, $request);
            } else {
                $resultado = $this->telefoniaService->procesarLogout($operador, $request->ip());
            }

            if (!isset($resultado['success']) || !$resultado['success']) {
                $mensajeError = $resultado['message'] ?? 'La AMI no respondió exitosamente.';
                return $this->errorResponse($log, 500, "Fallo en la operación AMI ({$evento}): {$mensajeError}");
            }

            $log->update(['codigo_respuesta' => 200]);

            return response()->json([
                'ok'   => true,
                'data' => [
                    'evento'     => $evento,
                    'operador'   => $operador->nombre_operador,
                    'extension'  => $operador->extension,
                    'cola'       => $operador->queue_name,
                    'ami_status' => $resultado['status'] ?? 'OK',
                    'dry_run'    => $resultado['dry_run'] ?? false,
                    'message'    => $resultado['message'] ?? 'Operación ejecutada con éxito.',
                ],
            ], 200);

        } catch (Throwable $e) {
            return $this->errorResponse($log, 500, 'Excepción en Servidor Receptor: ' . $e->getMessage());
        }
    }

    /**
     * Heartbeat endpoint for active operator sessions to prevent phantom sessions.
     */
    public function heartbeat(Request $request): JsonResponse
    {
        $request->validate(['ficha_username' => 'required|string']);
        
        $operador = OperadorConfig::where('ficha_username', $request->ficha_username)->first();
        if ($operador && $operador->is_active) {
            $session = OperadorSession::where('operador_config_id', $operador->id)
                ->whereNull('fecha_fin')
                ->latest()
                ->first();
                
            if ($session) {
                $session->update(['last_ping_at' => now()]);
                return response()->json(['ok' => true, 'message' => 'Heartbeat received']);
            }
        }
        
        return response()->json(['ok' => false, 'message' => 'No active session found'], 404);
    }

    /**
     * Health-check: estado del servicio receptor, conexión AMI real (Ping/Pong)
     * y enlace API FreePBX (GraphQL).
     */
    public function status(): JsonResponse
    {
        $operadoresActivos = OperadorConfig::where('is_active', true)->count();
        $totalOperadores = OperadorConfig::count();
        $dryRun = (bool) config('ami.dry_run', false);

        // ---------------------------------------------------------------
        // A. Verificación Real de AMI (Ping / Pong)
        // ---------------------------------------------------------------
        $amiOk  = false;
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
                    try {
                        @fgets($socket, 1024);
                        fwrite($socket, "Action: Ping\r\n\r\n");

                        $response = '';
                        $readTimeout = (int) config('ami.read_timeout', 3);
                        stream_set_timeout($socket, $readTimeout);

                        while (!feof($socket)) {
                            $line = fgets($socket, 1024);
                            if ($line === false) break;
                            $response .= $line;
                            if (trim($line) === '') break;
                        }
                    } finally {
                        if (is_resource($socket)) fclose($socket);
                    }

                    if (str_contains($response, 'Response: Success') && str_contains($response, 'Ping: Pong')) {
                        $amiOk  = true;
                        $amiMsg = 'Conectado (Ping/Pong OK)';
                    } else {
                        $amiMsg = 'Puerto abierto pero respuesta AMI inesperada: ' . trim(substr($response, 0, 200));
                    }
                } else {
                    $amiMsg = "No disponible: {$errstr} ({$errno})";
                }
            } catch (Throwable $e) {
                $amiMsg = 'Error: ' . $e->getMessage();
            }
        }

        // ---------------------------------------------------------------
        // B. Verificación del Enlace API FreePBX (GraphQL)
        // ---------------------------------------------------------------
        $freePbxOk  = false;
        $freePbxMsg = 'No verificado';

        try {
            $baseUrl  = config('services.freepbx.url');
            $apiToken = config('services.freepbx.api_token');

            if (empty($baseUrl) || empty($apiToken)) {
                $freePbxMsg = 'Configuración incompleta (URL o Token no definidos)';
            } else {
                $graphqlUrl = rtrim($baseUrl, '/') . '/api/graphql';
                $timeout    = (int) config('services.freepbx.health_check_timeout', 3);

                $httpResponse = Http::timeout($timeout)
                    ->withOptions(['verify' => false])
                    ->withHeaders([
                        'Content-Type'  => 'application/json',
                        'Authorization' => 'Bearer ' . $apiToken,
                    ])
                    ->post($graphqlUrl, [
                        'query' => '{ fetchExtensions { totalCount } }',
                    ]);

                if ($httpResponse->successful() && !isset($httpResponse->json()['errors'])) {
                    $freePbxOk  = true;
                    $freePbxMsg = 'Conectado';
                } else {
                    $freePbxMsg = $httpResponse->successful()
                        ? 'Respuesta con errores GraphQL: ' . json_encode($httpResponse->json()['errors'] ?? [])
                        : 'HTTP ' . $httpResponse->status();
                }
            }
        } catch (Throwable $e) {
            $freePbxMsg = 'Error: ' . $e->getMessage();
        }

        return response()->json([
            'ok'   => true,
            'data' => [
                'servicio'   => 'PBX Receptor',
                'version'    => '2.0.0',
                'ami'        => [
                    'conectado' => $amiOk,
                    'dry_run'   => $dryRun,
                    'mensaje'   => $amiMsg,
                    'host'      => config('ami.host') . ':' . config('ami.port'),
                ],
                'freepbx_api' => [
                    'conectado' => $freePbxOk,
                    'mensaje'   => $freePbxMsg,
                    'url'       => config('services.freepbx.url'),
                ],
                'operadores' => [
                    'activos' => $operadoresActivos,
                    'total'   => $totalOperadores,
                ],
                'timestamp'  => now()->toIso8601String(),
            ],
        ]);
    }

    public function listarOperadores(): JsonResponse
    {
        $operadores = OperadorConfig::orderBy('nombre_operador')
            ->get(['id', 'ficha_username', 'nombre_operador', 'extension', 'queue_name', 'is_active', 'updated_at']);

        return response()->json(['ok' => true, 'data' => $operadores]);
    }

    public function cambiarExtension(Request $request, $id): JsonResponse
    {
        $request->validate(['nueva_extension' => 'required|string']);
        $nuevaExtension = $request->input('nueva_extension');

        $operador = OperadorConfig::findOrFail($id);
        
        if ($operador->is_active) {
            $this->ami->removeFromQueue($operador->extension, $operador->queue_name);
        }

        $extAnt = Extension::where('numero', $operador->extension)->first();
        if ($extAnt) $extAnt->markAsFree();

        $extNue = Extension::where('numero', $nuevaExtension)->first();
        if ($extNue) $extNue->markAsInUse();

        $operador->update(['extension' => $nuevaExtension]);

        if ($operador->is_active) {
            $this->ami->addToQueue($operador->extension, $operador->queue_name, $operador->nombre_operador);
        }

        return response()->json(['ok' => true, 'mensaje' => "Extensión actualizada a {$nuevaExtension}"]);
    }

    public function statusCola($queue): JsonResponse
    {
        $status = $this->ami->getQueueStatus($queue);
        return response()->json(['ok' => true, 'data' => $status]);
    }

    private function errorResponse(LogApiReceptor $log, int $code, string $mensaje): JsonResponse
    {
        $log->update([
            'codigo_respuesta' => $code,
            'mensaje_error'    => $mensaje,
        ]);

        Log::warning("[Receptor] Error {$code}: {$mensaje}");

        return response()->json([
            'ok'    => false,
            'error' => [
                'code'    => $code,
                'message' => $mensaje,
            ],
        ], $code);
    }
}