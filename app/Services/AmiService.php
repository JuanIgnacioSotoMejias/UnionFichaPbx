<?php

namespace App\Services;

use App\Models\BitacoraAmi;
use PAMI\Client\Impl\ClientImpl as PamiClient;
use PAMI\Message\Action\QueueAddAction;
use PAMI\Message\Action\QueueRemoveAction;
use Illuminate\Support\Facades\Log;

/**
 * Servicio de comunicación con FreePBX via Asterisk Manager Interface (AMI).
 *
 * Usa la librería PAMI (marcelog/pami) para abrir un socket TCP al puerto 5038
 * de Asterisk y ejecutar las acciones QueueAdd / QueueRemove.
 *
 * En modo DRY_RUN (AMI_DRY_RUN=true en .env), simula las acciones sin conectar
 * al servidor real. Ideal para desarrollo sin FreePBX disponible.
 */
class AmiService
{
    private bool $dryRun;

    public function __construct()
    {
        $this->dryRun = (bool) config('ami.dry_run', false);
    }

    // -------------------------------------------------------------------------
    // Acciones públicas
    // -------------------------------------------------------------------------

    /**
     * Agrega una extensión a la cola de llamadas (LOGIN del operador).
     */
    public function addToQueue(string $extension, string $queue): array
    {
        $comando = 'QueueAdd';
        Log::info("[AMI] {$comando} extension={$extension} queue={$queue}" . ($this->dryRun ? ' [DRY_RUN]' : ''));

        if ($this->dryRun) {
            return $this->logAndReturn($comando, $extension, '[DRY_RUN] QueueAdd simulado correctamente', 'DRY_RUN');
        }

        try {
            $client = $this->conectar();
            $action = new QueueAddAction($queue, "SIP/{$extension}");
            $action->setKey('MemberName', "Operador {$extension}");
            $action->setKey('Penalty', '0');

            $response = $client->send($action);
            $respuesta = $response->getKey('Message') ?? $response->getKey('Response') ?? 'Sin respuesta';
            $status = ($response->getKey('Response') === 'Success') ? 'SUCCESS' : 'ERROR';

            $client->disconnect();

            return $this->logAndReturn($comando, $extension, $respuesta, $status);

        } catch (\Throwable $e) {
            Log::error("[AMI] QueueAdd falló: " . $e->getMessage());
            return $this->logAndReturn($comando, $extension, 'ERROR: ' . $e->getMessage(), 'ERROR');
        }
    }

    /**
     * Elimina una extensión de la cola de llamadas (LOGOUT del operador).
     */
    public function removeFromQueue(string $extension, string $queue): array
    {
        $comando = 'QueueRemove';
        Log::info("[AMI] {$comando} extension={$extension} queue={$queue}" . ($this->dryRun ? ' [DRY_RUN]' : ''));

        if ($this->dryRun) {
            return $this->logAndReturn($comando, $extension, '[DRY_RUN] QueueRemove simulado correctamente', 'DRY_RUN');
        }

        try {
            $client = $this->conectar();
            $action = new QueueRemoveAction($queue, "SIP/{$extension}");

            $response = $client->send($action);
            $respuesta = $response->getKey('Message') ?? $response->getKey('Response') ?? 'Sin respuesta';
            $status = ($response->getKey('Response') === 'Success') ? 'SUCCESS' : 'ERROR';

            $client->disconnect();

            return $this->logAndReturn($comando, $extension, $respuesta, $status);

        } catch (\Throwable $e) {
            Log::error("[AMI] QueueRemove falló: " . $e->getMessage());
            return $this->logAndReturn($comando, $extension, 'ERROR: ' . $e->getMessage(), 'ERROR');
        }
    }

    // -------------------------------------------------------------------------
    // Helpers privados
    // -------------------------------------------------------------------------

    /**
     * Abre y autentica la conexión con el servidor AMI de FreePBX.
     */
    private function conectar(): PamiClient
    {
        $options = [
            'host'             => config('ami.host'),
            'port'             => config('ami.port'),
            'username'         => config('ami.user'),
            'secret'           => config('ami.secret'),
            'connect_timeout'  => config('ami.connect_timeout', 3),
            'read_timeout'     => config('ami.read_timeout', 3),
            'scheme'           => 'tcp://',
        ];

        $client = new PamiClient($options);
        $client->open();

        return $client;
    }

    /**
     * Guarda la acción en bitacora_ami y devuelve el resultado estandarizado.
     */
    private function logAndReturn(string $comando, string $extension, string $respuesta, string $status): array
    {
        BitacoraAmi::create([
            'comando_enviado'    => $comando,
            'extension'          => $extension,
            'respuesta_asterisk' => $respuesta,
            'status'             => in_array($status, ['SUCCESS', 'ERROR']) ? $status : 'SUCCESS',
        ]);

        return [
            'status'    => $status,
            'extension' => $extension,
            'respuesta' => $respuesta,
            'dry_run'   => $this->dryRun,
        ];
    }
}
