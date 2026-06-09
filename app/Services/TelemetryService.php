<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\OperadorSession;
use Illuminate\Support\Carbon;

class TelemetryService
{
    /**
     * Calcula el Average Handling Time (AHT) del operador para el día actual.
     * Retorna 0 si no hay llamadas o extensión registrada.
     */
    public function getOperatorAHT($extension)
    {
        if (empty($extension) || $extension === '0000') {
            return $this->formatAHT(0);
        }

        $hoy = Carbon::today();

        // Consulta a la base de datos externa de Asterisk (CDR)
        $llamadas = DB::connection('freepbx')->table('cdr')
            ->whereDate('calldate', $hoy)
            ->where('disposition', 'ANSWERED')
            ->where(function ($query) use ($extension) {
                // Filtra llamadas donde la extensión haya sido origen o destino
                $query->where('src', $extension)
                      ->orWhere('dst', $extension)
                      ->orWhere('channel', 'like', 'PJSIP/' . $extension . '-%')
                      ->orWhere('dstchannel', 'like', 'PJSIP/' . $extension . '-%');
            })
            // Extrae el conteo de llamadas y suma los segundos (billsec)
            ->selectRaw('COUNT(*) as conteo_llamadas, SUM(billsec) as total_hablado')
            ->first();

        $conteo = $llamadas->conteo_llamadas ?? 0;
        $totalHablado = $llamadas->total_hablado ?? 0;

        if ($conteo == 0) {
            return $this->formatAHT(0);
        }

        // AHT = Tiempo Hablado / Número de Llamadas Contestadas
        $ahtSegundos = $totalHablado / $conteo;

        return $this->formatAHT($ahtSegundos);
    }

    /**
     * Calcula el porcentaje de ocupación del operador en su jornada de hoy.
     */
    public function getOperatorOccupation($extension, $userId)
    {
        if (empty($extension) || empty($userId) || $extension === '0000') {
            return 0.0;
        }

        $hoy = Carbon::today();

        // 1. Obtener tiempo total hablado sumando billsec
        $totalHablado = DB::connection('freepbx')->table('cdr')
            ->whereDate('calldate', $hoy)
            ->where('disposition', 'ANSWERED')
            ->where(function ($query) use ($extension) {
                $query->where('src', $extension)
                      ->orWhere('dst', $extension)
                      ->orWhere('channel', 'like', 'PJSIP/' . $extension . '-%')
                      ->orWhere('dstchannel', 'like', 'PJSIP/' . $extension . '-%');
            })
            ->sum('billsec');

        if (!$totalHablado) {
            $totalHablado = 0;
        }

        // 2. Tiempo Total de Sesión Activa del operador (Base de datos local)
        $sesionesHoy = OperadorSession::where('operador_config_id', $userId)
            ->whereDate('fecha_inicio', $hoy)
            ->get();

        $tiempoTotalSesionSegundos = 0;

        foreach ($sesionesHoy as $sesion) {
            $inicio = Carbon::parse($sesion->fecha_inicio);
            // Si la sesión no ha finalizado, calculamos hasta el momento actual
            $fin = $sesion->fecha_fin ? Carbon::parse($sesion->fecha_fin) : Carbon::now();
            $tiempoTotalSesionSegundos += $inicio->diffInSeconds($fin);
        }

        // Validación de Error por División Cero
        if ($tiempoTotalSesionSegundos == 0) {
            return 0.0;
        }

        // Ocupación = (Hablando / Tiempo de Sesión) * 100
        $ocupacion = ($totalHablado / $tiempoTotalSesionSegundos) * 100;

        return round(min(100, max(0, $ocupacion)), 1);
    }

    /**
     * Calcula el AHT y la Ocupación para una extensión en un rango de fechas.
     * (Mantenemos esta compatibilidad para el reporte general inferior)
     */
    public function calculateMetrics(int $operadorId, string $extension, Carbon $from, Carbon $to): array
    {
        if (empty($extension) || $extension === '0000') {
            return ['aht' => 0, 'ocupacion' => 0];
        }

        try {
            $llamadas = DB::connection('freepbx')->table('cdr')
                ->where(function ($q) use ($extension) {
                    $q->where('src', $extension)->orWhere('dst', $extension);
                })
                ->whereBetween('calldate', [$from, $to])
                ->where('disposition', 'ANSWERED')
                ->selectRaw('COUNT(*) as total_llamadas, SUM(billsec) as total_hablado')
                ->first();

            $totalLlamadas = $llamadas->total_llamadas ?? 0;
            $totalHablado = $llamadas->total_hablado ?? 0;
            
            $aht = $totalLlamadas > 0 ? ($totalHablado / $totalLlamadas) : 0;
            
        } catch (\Exception $e) {
            $totalLlamadas = 0;
            $totalHablado = 0;
            $aht = 0;
        }

        $sessions = OperadorSession::where('operador_config_id', $operadorId)
            ->whereBetween('fecha_inicio', [$from, $to])
            ->get();

        $totalSessionSeconds = $sessions->sum(function($session) {
            $end = $session->fecha_fin ? Carbon::parse($session->fecha_fin) : Carbon::now();
            return Carbon::parse($session->fecha_inicio)->diffInSeconds($end);
        });

        $ocupacion = 0;
        if ($totalSessionSeconds > 0) {
            $ocupacion = ($totalHablado / $totalSessionSeconds) * 100;
        }

        $ocupacion = min(100, max(0, $ocupacion));

        return [
            'aht' => round($aht, 1),
            'ocupacion' => round($ocupacion, 1),
            'total_hablado' => $totalHablado,
            'total_sesion' => $totalSessionSeconds
        ];
    }

    /**
     * Formatea los segundos de la base de datos a un formato legible en el Dashboard.
     * Soluciona el error de método indefinido en la vista Blade.
     */
    public function formatAHT($seconds)
    {
        try {
            if (!is_numeric($seconds) || $seconds <= 0) {
                return '0.0s';
            }

            $seconds = round((float)$seconds);
            $minutes = floor($seconds / 60);
            $remainingSeconds = $seconds % 60;

            if ($minutes > 0) {
                return "{$minutes}m {$remainingSeconds}s";
            }

            return "{$remainingSeconds}s";
        } catch (\Throwable $e) {
            return '0.0s';
        }
    }
}
