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
        
        // Eager load historial count en una sola query
        $operadores = OperadorConfig::withCount(['historial as logins_count' => function($q) use ($from, $to) {
                $q->where('evento', 'LOGIN')->whereBetween('created_at', [$from, $to]);
            }])
            ->get();

        // Batch: obtener alertas count para todos los operadores en una sola query
        $alertasCounts = DB::table('alertas_productividad')
            ->whereIn('operador_config_id', $operadores->pluck('id'))
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('operador_config_id, COUNT(*) as total')
            ->groupBy('operador_config_id')
            ->pluck('total', 'operador_config_id');

        // Batch: obtener métricas CDR para todas las extensiones en una sola query
        $extensiones = $operadores->pluck('extension')->filter()->unique()->values()->toArray();
        $cdrMetrics = [];
        
        if (!empty($extensiones)) {
            try {
                $cdrMetrics = DB::connection('freepbx')->table('cdr')
                    ->where('disposition', 'ANSWERED')
                    ->whereBetween('calldate', [$from, $to])
                    ->where(function ($query) use ($extensiones) {
                        $query->whereIn('src', $extensiones)
                              ->orWhereIn('dst', $extensiones);
                    })
                    ->selectRaw("
                        CASE 
                            WHEN src IN ('" . implode("','", $extensiones) . "') THEN src 
                            ELSE dst 
                        END as ext,
                        COUNT(*) as total_llamadas, 
                        SUM(billsec) as total_hablado
                    ")
                    ->groupByRaw("CASE WHEN src IN ('" . implode("','", $extensiones) . "') THEN src ELSE dst END")
                    ->get()
                    ->keyBy('ext');
            } catch (\Exception $e) {
                $cdrMetrics = collect();
            }
        } else {
            $cdrMetrics = collect();
        }

        // Batch: obtener sesiones de todos los operadores en una sola query
        $allSessions = OperadorSession::whereIn('operador_config_id', $operadores->pluck('id'))
            ->whereBetween('fecha_inicio', [$from, $to])
            ->get()
            ->groupBy('operador_config_id');

        return $operadores->map(function($op) use ($from, $to, $cdrMetrics, $allSessions, $alertasCounts) {
            $ext = $op->extension ?? '';
            $cdr = $cdrMetrics[$ext] ?? null;
            
            $totalLlamadas = $cdr->total_llamadas ?? 0;
            $totalHablado = $cdr->total_hablado ?? 0;
            $aht = $totalLlamadas > 0 ? round($totalHablado / $totalLlamadas, 1) : 0;

            // Calcular ocupación desde sesiones en batch
            $sessions = $allSessions[$op->id] ?? collect();
            $totalSessionSeconds = $sessions->sum(function($session) {
                $end = $session->fecha_fin ? Carbon::parse($session->fecha_fin) : Carbon::now();
                return Carbon::parse($session->fecha_inicio)->diffInSeconds($end);
            });

            $ocupacion = 0;
            if ($totalSessionSeconds > 0) {
                $ocupacion = round(min(100, max(0, ($totalHablado / $totalSessionSeconds) * 100)), 1);
            }

            return [
                'id' => $op->id,
                'nombre' => $op->nombre_operador,
                'extension' => $op->extension,
                'aht' => $aht,
                'ocupacion' => $ocupacion,
                'alertas_count' => $alertasCounts[$op->id] ?? 0,
            ];
        })->toArray();
    }
}
