<?php

namespace App\Console\Commands;

use App\Jobs\AnalyzeCallQualityJob;
use App\Services\IntelligenceService;
use PAMI\Client\Impl\ClientImpl as PamiClient;
use PAMI\Message\Event\EventMessage;
use Illuminate\Support\Facades\Log;

class AmiMonitorCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'ami:monitor';

    /**
     * @var string
     */
    protected $description = 'Monitorea eventos AMI en tiempo real para detección de anomalías y fugas de cola.';

    /**
     * Execute the console command.
     */
    public function handle(IntelligenceService $intelligence): void
    {
        if (config('ami.dry_run')) {
            $this->warn('Modo DRY_RUN activo. El monitor no puede iniciar sin conexión real.');
            $this->info('Simulando monitor... presiona Ctrl+C para salir.');
            while(true) { sleep(10); } // Loop de simulación
            return;
        }

        $this->info('Conectando con Asterisk AMI...');

        try {
            $options = [
                'host'            => config('ami.host'),
                'port'            => config('ami.port'),
                'username'        => config('ami.user'),
                'secret'          => config('ami.secret'),
                'connect_timeout' => config('ami.connect_timeout'),
                'read_timeout'    => 10000,
                'scheme'          => 'tcp://',
            ];

            $client = new PamiClient($options);
            $client->open();

            $client->registerEventListener(function (EventMessage $event) use ($intelligence) {
                $this->processEvent($event, $intelligence);
            });

            $this->info('Monitor activo. Escuchando eventos financieros...');

            while (true) {
                $client->process();
                usleep(100000);
            }

            $client->close();
        } catch (\Throwable $e) {
            $this->error('Fallo crítico en Monitor AMI: ' . $e->getMessage());
            Log::error('[AmiMonitor] Fatal Error: ' . $e->getMessage());
        }
    }

    /**
     * Procesa los eventos entrantes y activa la lógica de inteligencia.
     */
    private function processEvent(EventMessage $event, IntelligenceService $intelligence): void
    {
        $eventName = $event->getName();

        // 1. Análisis de Calidad de Llamada (Hangup)
        if ($eventName === 'Hangup') {
            $billsec = (int) $event->getKey('billsec'); // En algunos sistemas viene aquí
            $uniqueid = $event->getKey('uniqueid');
            $extension = $event->getKey('calleridnum');

            if ($billsec > 0) {
                AnalyzeCallQualityJob::dispatch([
                    'uniqueid' => $uniqueid,
                    'extension' => $extension,
                    'duration' => $billsec,
                    'event' => 'Hangup'
                ]);
            }
        }

        // 2. Fugas de Cola (Abandono)
        if ($eventName === 'QueueCallerAbandon') {
            $intelligence->detectQueueLeak([
                'queue' => $event->getKey('queue'),
                'uniqueid' => $event->getKey('uniqueid'),
                'HoldTime' => $event->getKey('holdtime'),
                'event' => 'QueueCallerAbandon'
            ]);
        }
    }
}
