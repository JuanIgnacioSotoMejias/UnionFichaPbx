<?php

namespace App\Services;

use App\Events\AlertaCreada;
use App\Models\AlertaProductividad;
use App\Models\OperadorSession;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Servicio de Inteligencia de Eventos.
 * Analiza sesiones y llamadas para detectar anomalías operativas.
 */
class IntelligenceService
{
    /**
     * Analiza si una sesión iniciada o finalizada tiene irregularidades respecto al turno programado.
     */
    public function analyzeSession(OperadorSession $session): void
    {
        $horaInicio = Carbon::parse($session->fecha_inicio);
        
        // 1. Validar Inicio de Sesión Tardío (ej: más de 5 min de retraso)
        if ($session->hora_inicio_esperada) {
            $esperado = Carbon::createFromFormat('H:i:s', $session->hora_inicio_esperada)
                ->setDate($horaInicio->year, $horaInicio->month, $horaInicio->day);
            
            if ($horaInicio->isAfter($esperado->addMinutes(5))) {
                $this->createAlert($session, 'LATE_LOGIN', 'WARNING', "Inicio de sesión tardío: {$horaInicio->format('H:i:s')} (esperado: {$session->hora_inicio_esperada})");
            }
        }

        // 2. Validar Cierre de Sesión Prematuro
        if ($session->fecha_fin && $session->hora_fin_esperada) {
            $horaFin = Carbon::parse($session->fecha_fin);
            $esperadoFin = Carbon::createFromFormat('H:i:s', $session->hora_fin_esperada)
                ->setDate($horaFin->year, $horaFin->month, $horaFin->day);

            if ($horaFin->isBefore($esperadoFin->subMinutes(5))) {
                $this->createAlert($session, 'EARLY_LOGOUT', 'CRITICAL', "Cierre de sesión prematuro: {$horaFin->format('H:i:s')} (esperado: {$session->hora_fin_esperada})");
            }
        }
    }

    /**
     * Analiza datos de una llamada para detectar duraciones sospechosamente cortas.
     */
    public function analyzeCall(array $eventData): void
    {
        $duration = (int) ($eventData['duration'] ?? 0);
        $threshold = config('ami.short_call_threshold', 10);
        $extension = $eventData['extension'] ?? 'Unknown';

        if ($duration > 0 && $duration < $threshold) {
            $operador = \App\Models\OperadorConfig::where('extension', $extension)->first();
            
            $alerta = AlertaProductividad::create([
                'operador_config_id' => $operador?->id,
                'tipo_alerta' => 'SHORT_CALL',
                'nivel' => 'WARNING',
                'descripcion' => "Llamada de corta duración detectada ({$duration}s) en extensión {$extension}.",
                'metadatos' => $eventData,
            ]);

            event(new AlertaCreada($alerta));

            Log::warning("[Intelligence] Alerta SHORT_CALL: Ext {$extension} | Duración {$duration}s");
        }
    }

    /**
     * Detecta "Fugas de Cola" (llamadas abandonadas o no atendidas).
     */
    public function detectQueueLeak(array $queueData): void
    {
        // Lógica para detectar si una llamada entró a cola pero no fue asignada satisfactoriamente
        if ($queueData['event'] === 'QueueCallerAbandon') {
            $alerta = AlertaProductividad::create([
                'tipo_alerta' => 'QUEUE_LEAK',
                'nivel' => 'CRITICAL',
                'descripcion' => "Abandono en cola detectado. Tiempo en espera: {$queueData['HoldTime']}s",
                'metadatos' => $queueData,
            ]);

            event(new AlertaCreada($alerta));
        }
    }

    /**
     * Helper para crear alertas vinculadas a una sesión.
     */
    private function createAlert(OperadorSession $session, string $tipo, string $nivel, string $descripcion): void
    {
        $alerta = AlertaProductividad::create([
            'operador_config_id' => $session->operador_config_id,
            'tipo_alerta' => $tipo,
            'nivel' => $nivel,
            'descripcion' => $descripcion,
            'metadatos' => [
                'session_id' => $session->id,
                'fecha' => $session->fecha_inicio->toDateString(),
            ]
        ]);

        event(new AlertaCreada($alerta));

        Log::info("[Intelligence] Alerta {$tipo}: {$descripcion}");
    }
}
