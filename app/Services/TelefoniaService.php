<?php

namespace App\Services;

use App\Models\Extension;
use App\Models\HistorialAcceso;
use App\Models\OperadorConfig;
use App\Models\OperadorSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class TelefoniaService
{
    public function __construct(
        private readonly AmiService $ami,
        private readonly IntelligenceService $intelligence
    ) {}

    /**
     * Busca el operador o lo crea, y actualiza sus datos si vienen en el Request.
     */
    public function gestionarOperador(string $fichaUsername, Request $request): OperadorConfig
    {
        $operador = OperadorConfig::findByFichaUsername($fichaUsername);

        $extensionPayload = $request->input('extension');
        $colaPayload      = $request->input('cola');
        $nombrePayload    = $request->input('nombre');

        if (!$operador) {
            $operador = OperadorConfig::create([
                'ficha_username'  => $fichaUsername,
                'nombre_operador' => $nombrePayload ?? $fichaUsername,
                'extension'       => $extensionPayload ?? '0000', 
                'queue_name'      => $colaPayload ?? 'default',
                'is_active'       => false,
            ]);
            Log::info("[TelefoniaService] Auto-registro de operador: {$fichaUsername}");
        } else {
            $updateData = array_filter([
                'queue_name'      => $colaPayload,
                'nombre_operador' => $nombrePayload,
                'extension'       => $extensionPayload,
            ]);
            
            if (!empty($updateData)) {
                $operador->update($updateData);
            }
        }

        return $operador;
    }

    /**
     * Procesa el login: asigna extensión, la marca en uso, y añade a la cola AMI.
     */
    public function procesarLogin(OperadorConfig $operador, Request $request): array
    {
        $ip = $request->ip();
        $extensionUsar = $operador->extension;

        // Si no tiene extensión, intentar asignar (cardinalidad 1:1, si hay choque se asigna una libre)
        if ($extensionUsar === '0000' || empty($extensionUsar)) {
            $extensionPayload = $request->input('extension');

            if (!empty($extensionPayload) && $extensionPayload !== '0000') {
                $extensionUsar = $extensionPayload;
                $operador->update(['extension' => $extensionUsar]);
            } else {
                // FALLBACK: Verificar primero si tiene extensiones asignadas en la relación muchos a muchos
                $relationExt = $operador->extensiones()->first();
                if ($relationExt) {
                    $extensionUsar = $relationExt->numero;
                    $operador->update(['extension' => $extensionUsar]);
                } else {
                    $extAutoAsignada = Extension::autoAsignar($operador);

                    if (!$extAutoAsignada) {
                        return [
                            'success' => false,
                            'status'  => 'ERROR',
                            'message' => 'No hay extensiones disponibles para asignar al operador.',
                            'dry_run' => (bool) config('ami.dry_run', false),
                        ];
                    }

                    $extensionUsar = $extAutoAsignada->numero;
                }
            }
        }

        $extModel = Extension::where('numero', $extensionUsar)->first();
        if ($extModel) {
            $extModel->asignarA($operador);
        }

        $amiResponse = $this->ami->addToQueue(
            $extensionUsar,
            $operador->queue_name,
            $operador->nombre_operador
        );

        if (!isset($amiResponse['success']) || !$amiResponse['success']) {
            Log::warning("[TelefoniaService] LOGIN fallido en AMI para {$operador->ficha_username}");
            if ($extModel) $extModel->markAsFree();

            return array_merge([
                'success' => false,
                'status'  => 'ERROR',
                'message' => 'Fallo al registrar extensión en la cola AMI.',
                'dry_run' => (bool) config('ami.dry_run', false),
            ], $amiResponse ?? []);
        }

        $operador->update(['is_active' => true]);

        OperadorSession::create([
            'operador_config_id' => $operador->id,
            'extension'          => $extensionUsar,
            'fecha_inicio'       => now(),
            'estado_actual'      => 'LOGIN',
            'last_ping_at'       => now(),
        ]);

        HistorialAcceso::create([
            'operador_config_id' => $operador->id,
            'evento'             => 'LOGIN',
            'origen_ip'          => $ip,
        ]);

        Log::info("[TelefoniaService] LOGIN exitoso: {$operador->ficha_username}");

        return array_merge([
            'success' => true,
            'status'  => 'Conectado',
            'message' => "Operador registrado en cola.",
            'dry_run' => (bool) config('ami.dry_run', false),
        ], $amiResponse ?? []);
    }

    /**
     * Procesa el logout: retira de cola AMI y libera recursos.
     */
    public function procesarLogout(OperadorConfig $operador, string $ip): array
    {
        $resultado = [
            'success' => true,
            'status'  => 'No Action',
            'message' => 'El operador no tenía una extensión activa.'
        ];

        $extensionUsar = $operador->extension;

        if ($extensionUsar !== '0000' && !empty($extensionUsar)) {
            try {
                $amiResponse = $this->ami->removeFromQueue($extensionUsar, $operador->queue_name);
                $resultado = array_merge($resultado, $amiResponse ?? []);
            } catch (\Exception $e) {
                // Capturar excepción de Asterisk silenciosamente para no detener el flujo de logout local
                Log::warning("[TelefoniaService] Fallo al remover de cola AMI para {$operador->ficha_username}: " . $e->getMessage());
            }

            Extension::where('numero', $extensionUsar)->first()?->markAsFree();
            // Se elimina la sobreescritura a '0000' para mantener el histórico de la última extensión del día
            // $operador->update(['extension' => '0000']);
        }

        $operador->update(['is_active' => false]);

        $session = OperadorSession::where('operador_config_id', $operador->id)
            ->whereNull('fecha_fin')
            ->latest()
            ->first();

        if ($session) {
            $session->update([
                'fecha_fin'     => now(),
                'estado_actual' => 'LOGOUT',
            ]);
            $this->intelligence->analyzeSession($session);
        }

        HistorialAcceso::create([
            'operador_config_id' => $operador->id,
            'evento'             => 'LOGOUT',
            'origen_ip'          => $ip,
        ]);

        Log::info("[TelefoniaService] LOGOUT procesado: {$operador->ficha_username}");

        return $resultado;
    }
}
