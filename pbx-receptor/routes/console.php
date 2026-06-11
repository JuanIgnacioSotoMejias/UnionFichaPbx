<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Tarea para reiniciar operadores (cambio de turno)
Schedule::command('app:reset-operators')->dailyAt('08:30');

// Anti-phantom sessions: verifica heartbeat cada minuto y cierra sesiones zombies
Schedule::command('pbx:close-phantom-sessions')->everyMinute()->withoutOverlapping();

// Verificar estado AMI de extensiones activas y disparar alertas offline
Schedule::command('extensions:check-status')->everyMinute()->withoutOverlapping();
