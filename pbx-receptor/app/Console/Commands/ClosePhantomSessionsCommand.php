<?php

namespace App\Console\Commands;

use App\Models\Extension;
use App\Models\HistorialAcceso;
use App\Models\OperadorConfig;
use App\Models\OperadorSession;
use App\Services\AmiService;
use App\Services\IntelligenceService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Limpia sesiones fantasma: operadores que dejaron de enviar
 * heartbeat (cerraron el navegador sin hacer logout).
 * Debe ejecutarse cada minuto via scheduler.
 */
class ClosePhantomSessionsCommand extends Command
{
    protected $signature   = 'pbx:close-phantom-sessions {--timeout=120 : Segundos sin ping antes de considerar sesión fantasma}';
    protected $description = 'Cierra automáticamente sesiones operadoras sin heartbeat reciente (anti-phantom).';

    public function __construct(
        private readonly AmiService $ami,
        private readonly IntelligenceService $intelligence
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $timeoutSeconds = (int) $this->option('timeout');
        $cutoff = Carbon::now()->subSeconds($timeoutSeconds);

        // Sesiones abiertas cuyo último ping fue hace más del timeout
        $phantomSessions = OperadorSession::whereNull('fecha_fin')
            ->where(function ($q) use ($cutoff) {
                $q->where('last_ping_at', '<', $cutoff)
                  ->orWhereNull('last_ping_at'); // Sesiones antiguas sin campo ping
            })
            ->with('operadorConfig')
            ->get();

        if ($phantomSessions->isEmpty()) {
            $this->info('[Heartbeat] No hay sesiones fantasma activas.');
            return self::SUCCESS;
        }

        $this->warn("[Heartbeat] Se encontraron {$phantomSessions->count()} sesión(es) fantasma. Cerrando...");

        foreach ($phantomSessions as $session) {
            $operador = $session->operadorConfig;

            if (!$operador) {
                $session->update(['fecha_fin' => now(), 'estado_actual' => 'PHANTOM_LOGOUT']);
                continue;
            }

            try {
                // Retirar de cola AMI
                if ($operador->extension !== '0000' && !empty($operador->extension)) {
                    $this->ami->removeFromQueue($operador->extension, $operador->queue_name);
                    Extension::where('numero', $operador->extension)->first()?->markAsFree();
                    $operador->update(['extension' => '0000']);
                }

                // Desactivar operador en BD
                $operador->update(['is_active' => false]);

                // Cerrar sesión
                $session->update([
                    'fecha_fin'     => now(),
                    'estado_actual' => 'PHANTOM_LOGOUT',
                ]);

                $this->intelligence->analyzeSession($session->fresh());

                // Registrar en historial
                HistorialAcceso::create([
                    'operador_config_id' => $operador->id,
                    'evento'             => 'PHANTOM_LOGOUT',
                    'origen_ip'          => '0.0.0.0',
                ]);

                Log::warning("[Heartbeat] Sesión fantasma cerrada: {$operador->ficha_username} | Sesión #{$session->id}");
                $this->line("  ↳ Cerrada: {$operador->nombre_operador} (sesión #{$session->id})");

            } catch (\Throwable $e) {
                Log::error("[Heartbeat] Error cerrando sesión #{$session->id}: " . $e->getMessage());
                $this->error("  ✗ Error en sesión #{$session->id}: " . $e->getMessage());
            }
        }

        $this->info('[Heartbeat] Proceso completado.');
        return self::SUCCESS;
    }
}
