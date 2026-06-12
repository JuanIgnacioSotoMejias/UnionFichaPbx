<?php

namespace App\Console\Commands;

use App\Events\ExtensionOfflineAlert;
use App\Models\Extension;
use App\Services\AmiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CheckExtensionStatusCommand extends Command
{
    protected $signature = 'extensions:check-status';
    protected $description = 'Verifica el estado AMI de las extensiones activas y dispara alertas si alguna pasa a OFFLINE.';

    public function handle(AmiService $amiService): void
    {
        $extensions = Extension::where('is_active', true)->pluck('numero')->toArray();

        if (empty($extensions)) {
            $this->info('No hay extensiones activas para verificar.');
            return;
        }

        try {
            $statuses = $amiService->getExtensionsStatuses($extensions);
        } catch (\Throwable $e) {
            Log::warning('[CheckExtensionStatus] No se pudo conectar con AMI: ' . $e->getMessage());
            $this->warn('AMI inalcanzable: ' . $e->getMessage());
            return;
        }

        foreach ($statuses as $numero => $estado) {
            $cacheKey = "ext_status_{$numero}";
            $previousStatus = Cache::get($cacheKey, 'ONLINE');

            // Detectar transición: estaba ONLINE y ahora está OFFLINE
            if (strtoupper($estado) === 'OFFLINE' && strtoupper($previousStatus) !== 'OFFLINE') {
                $descripcion = "La extensión {$numero} ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.";

                Log::warning("[CheckExtensionStatus] Extensión {$numero} → OFFLINE");

                // Disparar evento broadcast al canal 'alertas'
                event(new ExtensionOfflineAlert($numero, $descripcion));

                $this->warn("⚠ Extensión {$numero} → OFFLINE (alerta disparada)");
            }

            // Almacenar estado actual en cache (TTL: 5 minutos)
            Cache::put($cacheKey, strtoupper($estado), now()->addMinutes(5));
        }

        $online = collect($statuses)->filter(fn($s) => strtoupper($s) === 'ONLINE')->count();
        $offline = collect($statuses)->filter(fn($s) => strtoupper($s) === 'OFFLINE')->count();

        $this->info("Verificación completada: {$online} online, {$offline} offline de " . count($extensions) . " extensiones.");
    }
}
