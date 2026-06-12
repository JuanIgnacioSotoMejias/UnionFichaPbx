<?php

namespace App\Services;

use App\Models\BitacoraAmi;
use Illuminate\Support\Facades\Log;
use PAMI\Client\Impl\ClientImpl as PamiClient;
use PAMI\Message\Action\QueueAddAction;
use PAMI\Message\Action\QueueRemoveAction;
use PAMI\Message\Action\ExtensionStateAction;
use PAMI\Message\Action\QueueStatusAction;

/**
 * Servicio para la comunicación con Asterisk vía AMI (Asterisk Manager Interface).
 */
class AmiService
{
    private bool $dryRun;

    public function __construct()
    {
        $this->dryRun = (bool) config('ami.dry_run', false);
    }

    /**
     * Consulta masiva de estados de extensiones vía AMI en una única sesión de socket.
     */
    public function getExtensionsStatuses(array $extensions): array
    {
        if ($this->dryRun) {
            return array_fill_keys($extensions, 'ONLINE');
        }

        try {
            $client = $this->conectar();
            $statuses = [];

            foreach ($extensions as $ext) {
                try {
                    $action = new ExtensionStateAction($ext, 'ext-local');
                    $response = $client->send($action);
                    
                    Log::debug("[AMI Debug] Cabeceras completas para extensión {$ext}:", $response->getKeys());

                    $statusRaw = $response->getKey('status');
                    $statusText = $response->getKey('statustext');

                    // Prioritize text if available, fallback to int code
                    if (!empty($statusText) && $statusText !== 'UNKNOWN') {
                        $statuses[$ext] = \App\Enums\PjsipStatus::fromText($statusText)->getSemanticStatus();
                    } else {
                        $statuses[$ext] = \App\Enums\PjsipStatus::fromCode($statusRaw !== null ? (int)$statusRaw : null)->getSemanticStatus();
                    }
                } catch (\Throwable $e) {
                    Log::warning("[AMI] Error individual en extensión {$ext}: " . $e->getMessage());
                    $statuses[$ext] = 'OFFLINE';
                }
            }

            $client->close();
            return $statuses;
        } catch (\Throwable $e) {
            Log::error("[AMI] Error masivo de conexión en el socket: " . $e->getMessage());
            throw new \RuntimeException("Asterisk temporalmente inalcanzable", 0, $e);
        }
    }

    /**
     * Añade una extensión a una cola de llamadas dinámica vía AMI.
     *
     * Construye el comando AMI QueueAdd con los parámetros completos:
     *  - Interface:      PJSIP/{extension}
     *  - Penalty:        0
     *  - Paused:         false
     *  - MemberName:     Nombre legible del operador
     *  - StateInterface: PJSIP/{extension}
     */
    public function addToQueue(string $extension, string $queue, string $memberName = ''): array
    {
        $interface = "PJSIP/{$extension}";

        $action = new QueueAddAction($queue, $interface);
        $action->setPenalty('0');
        $action->setPaused('false');
        $action->setMemberName($memberName ?: $extension);
        $action->setStateInterface($interface);

        return $this->executeQueueAction('QueueAdd', $extension, $queue, $action);
    }

    /**
     * Retira una extensión de una cola de llamadas dinámica vía AMI.
     *
     * Construye el comando AMI QueueRemove con:
     *  - Queue:     {queue}
     *  - Interface: PJSIP/{extension}  (debe coincidir con el usado en QueueAdd)
     */
    public function removeFromQueue(string $extension, string $queue): array
    {
        $interface = "PJSIP/{$extension}";

        return $this->executeQueueAction('QueueRemove', $extension, $queue, new QueueRemoveAction($queue, $interface));
    }

    /**
     * Obtiene el estado de una cola (ej. número de llamadas en espera).
     */
    public function getQueueStatus(string $queue): array
    {
        if ($this->dryRun) {
            return ['queue' => $queue, 'calls' => rand(0, 5), 'status' => 'online', 'dry_run' => true];
        }

        try {
            $client = $this->conectar();
            $action = new QueueStatusAction();
            $action->setQueue($queue);
            
            $response = $client->send($action);
            
            // PAMI QueueStatus returns multiple events. Simplificaremos capturando el success.
            // Para contar llamadas se requeriría procesar Eventos QueueParams, pero de base devolvemos ok.
            $client->close();
            
            return ['queue' => $queue, 'calls' => 0, 'status' => 'online', 'dry_run' => false];
        } catch (\Throwable $e) {
            Log::error("[AMI] Error en QueueStatus para cola {$queue}: " . $e->getMessage());
            return ['queue' => $queue, 'calls' => 0, 'status' => 'offline', 'error' => $e->getMessage()];
        }
    }

    /**
     * Método genérico para ejecutar acciones de cola.
     */
    private function executeQueueAction(string $comando, string $extension, string $queue, $action): array
    {
        if ($this->dryRun) {
            return $this->logAndReturn($comando, $extension, '[DRY_RUN] Simulado', 'DRY_RUN');
        }

        try {
            $client = $this->conectar();
            $response = $client->send($action);
            $respuesta = $response->getMessage();
            $status = $response->isSuccess() ? 'SUCCESS' : 'ERROR';
            $client->close();

            return $this->logAndReturn($comando, $extension, $respuesta, $status);
        } catch (\Throwable $e) {
            Log::error("[AMI] Error en {$comando}: " . $e->getMessage());
            return $this->logAndReturn($comando, $extension, $e->getMessage(), 'ERROR');
        }
    }

    /**
     * Establece una conexión AMI con Asterisk usando la configuración centralizada.
     */
    private function conectar(): PamiClient
    {
        $options = [
            'host'            => config('ami.host', '127.0.0.1'),
            'port'            => config('ami.port', 5038),
            'username'        => config('ami.user', ''),
            'secret'          => config('ami.secret', ''),
            'connect_timeout' => config('ami.connect_timeout', 3),
            'read_timeout'    => config('ami.read_timeout', 3),
            'scheme'          => 'tcp://',
        ];

        $client = new PamiClient($options);
        $client->open();

        return $client;
    }

    /**
     * Registra la acción en la bitácora AMI y retorna el resultado estructurado.
     */
    private function logAndReturn(string $comando, string $extension, string $respuesta, string $status): array
    {
        BitacoraAmi::create([
            'comando_enviado'    => $comando,
            'extension'          => $extension,
            'respuesta_asterisk' => $respuesta,
            'status'             => $status,
        ]);

        return [
            'success'   => in_array($status, ['SUCCESS', 'DRY_RUN']),
            'message'   => $respuesta,
            'status'    => $status,
            'extension' => $extension,
            'respuesta' => $respuesta,
            'dry_run'   => $this->dryRun,
        ];
    }
}