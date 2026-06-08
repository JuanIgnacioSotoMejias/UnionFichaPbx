<?php

namespace App\Services;

use App\Models\OperadorConfig;
use App\Models\OperadorSession;
use App\Models\AlertaProductividad;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Servicio para el cálculo de métricas de productividad (AHT, Ocupación, Eficiencia).
 */
class ProductivityReportService
{
    /**
     * Calcula el Average Handle Time (AHT) para un operador en un rango de fechas.
     * Basado en las llamadas procesadas (suponiendo que guardamos duraciones).
     */
    public function calculateAHT(int $operadorId, Carbon $from, Carbon $to): float
    {
        // Por ahora lo calculamos basándonos en los metadatos de las alertas SHORT_CALL 
        // y cualquier otra métrica que tengamos. En un entorno real, usaría una tabla 'calls'.
        $average = DB::table('alertas_productividad')
            ->where('operador_config_id', $operadorId)
            ->whereBetween('created_at', [$from, $to])
            ->whereJsonContains('metadatos->event', 'Hangup')
            ->avg(DB::raw('CAST(JSON_EXTRACT(metadatos, "$.duration") AS UNSIGNED)'));

        return (float) ($average ?? 0);
    }

    /**
     * Calcula el porcentaje de ocupación.
     * (Tiempo total en llamadas / Tiempo total de sesión) * 100
     */
    public function calculateOccupancy(int $operadorId, Carbon $from, Carbon $to): float
    {
        $sessions = OperadorSession::where('operador_config_id', $operadorId)
            ->whereBetween('fecha_inicio', [$from, $to])
            ->get();

        $totalSessionSeconds = $sessions->sum(function($session) {
            if (!$session->fecha_fin) return 0;
            return $session->fecha_inicio->diffInSeconds($session->fecha_fin);
        });

        if ($totalSessionSeconds === 0) return 0;

        // Sumar duración de todas las llamadas en ese periodo
        $totalCallSeconds = DB::table('alertas_productividad')
            ->where('operador_config_id', $operadorId)
            ->whereBetween('created_at', [$from, $to])
            ->whereJsonContains('metadatos->event', 'Hangup')
            ->sum(DB::raw('CAST(JSON_EXTRACT(metadatos, "$.duration") AS UNSIGNED)'));

        return round(($totalCallSeconds / $totalSessionSeconds) * 100, 2);
    }

    public function getSummaryReport(string $periodo = 'dia'): array
    {
        $today = Carbon::today();
        
        // Determinar rango de fechas
        $from = match($periodo) {
            'semana' => Carbon::now()->startOfWeek(),
            'mes' => Carbon::now()->startOfMonth(),
            default => $today
        };
        $to = match($periodo) {
            'semana' => Carbon::now()->endOfWeek(),
            'mes' => Carbon::now()->endOfMonth(),
            default => $today->copy()->endOfDay()
        };
        
        $telemetryService = app(TelemetryService::class);
        
        return OperadorConfig::withCount(['historial as logins_count' => function($q) use ($from, $to) {
                $q->where('evento', 'LOGIN')->whereBetween('created_at', [$from, $to]);
            }])
            ->get()
            ->map(function($op) use ($from, $to, $telemetryService) {
                $metrics = $telemetryService->calculateMetrics($op->id, $op->extension ?? '', $from, $to);
                
                return [
                    'id' => $op->id,
                    'nombre' => $op->nombre_operador,
                    'extension' => $op->extension,
                    'aht' => $metrics['aht'],
                    'ocupacion' => $metrics['ocupacion'],
                    'alertas_count' => AlertaProductividad::where('operador_config_id', $op->id)->whereBetween('created_at', [$from, $to])->count(),
                ];
            })
            ->toArray();
    }
}
