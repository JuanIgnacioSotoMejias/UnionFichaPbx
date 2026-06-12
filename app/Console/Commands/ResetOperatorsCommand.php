<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OperadorConfig;
use Illuminate\Support\Facades\Log;

class ResetOperatorsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-operators';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cambia todos los operadores a estado inactivo (is_active=false) para el cambio de turno';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('[Cron] Iniciando reinicio diario de operadores de turno (08:30 AM).');

        $updatedCount = OperadorConfig::where('is_active', true)->update(['is_active' => false]);

        Log::info("[Cron] Se han desactivado {$updatedCount} operadores exitosamente.");
        
        $this->info("Se han desactivado {$updatedCount} operadores exitosamente.");
    }
}
