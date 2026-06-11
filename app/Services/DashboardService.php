<?php

namespace App\Services;

use App\Models\BitacoraAmi;
use App\Models\HistorialAcceso;
use App\Models\LogApiReceptor;
use App\Models\OperadorConfig;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DashboardService
{
    public function __construct(
        private readonly FreePbxService $pbxService,
        private readonly AmiService $amiService,
        private readonly ProductivityReportService $productivityService,
        private readonly ScheduleValidationService $scheduleService
    ) {
    }

    /**
     * Obtiene todos los datos necesarios para el dashboard.
     * Retorna un arreglo asociativo con las variables que requiere la vista.
     *
     * OPTIMIZACIÓN v2: Todas las llamadas externas (FreePBX API, AMI socket)
     * están cacheadas con TTL cortos para no bloquear el hilo PHP.
     * Los estados AMI de operadores se cargan vía AJAX desde el frontend.
     */
    public function getDashboardData(string $periodo = 'dia'): array
    {
        // 1. Health Check + Datos GraphQL — Cacheados 30 segundos
        try {
            $amiConectado = Cache::remember('dashboard:pbx_connected', 30, function () {
                return $this->pbxService->checkRealtimeConnection();
            });

            $amiMensaje = $amiConectado
                ? 'API conectada a FreePBX'
                : 'Sin respuesta de FreePBX (timeout o sin ruta de red).';

            $extensionesPami = $amiConectado
                ? Cache::remember('dashboard:pbx_operadores', 30, function () {
                    return $this->pbxService->getOperadores();
                })
                : [];
        } catch (\Throwable $e) {
            $extensionesPami = [];
            $amiConectado    = false;
            $amiMensaje      = 'Servidor PBX temporalmente fuera de línea.';

            Log::warning('[Dashboard] Error al consultar FreePBX: ' . $e->getMessage());
        }

        // 2. Métricas generales del middleware local — Cacheadas 30 segundos
        try {
            $metricas = Cache::remember('dashboard:metricas_' . today()->toDateString(), 30, function () {
                return [
                    'totalOperadores'   => OperadorConfig::count(),
                    'operadoresActivos' => OperadorConfig::where('is_active', true)->count(),
                    'totalEventosHoy'   => HistorialAcceso::whereDate('created_at', today())->count(),
                    'erroresHoy'        => LogApiReceptor::where('codigo_respuesta', '>=', 400)
                        ->whereDate('created_at', today())
                        ->count(),
                ];
            });

            $totalOperadores   = $metricas['totalOperadores'];
            $operadoresActivos = $metricas['operadoresActivos'];
            $totalEventosHoy   = $metricas['totalEventosHoy'];
            $erroresHoy        = $metricas['erroresHoy'];
        } catch (\Throwable $e) {
            $totalOperadores = 0;
            $operadoresActivos = 0;
            $totalEventosHoy = 0;
            $erroresHoy = 0;

            Log::error('[Dashboard] Error al cargar métricas: ' . $e->getMessage());
        }

        // 3. Últimas actividades de la base de datos local
        try {
            $ultimosEventos = HistorialAcceso::with('operador')
                ->latest('created_at')
                ->paginate(7, ['*'], 'eventos_page');

            $ultimosErroresAmi = BitacoraAmi::where('status', 'ERROR')
                ->latest()
                ->take(5)
                ->get();
        } catch (\Throwable $e) {
            $ultimosEventos = collect();
            $ultimosErroresAmi = collect();

            Log::error('[Dashboard] Error al cargar actividades: ' . $e->getMessage());
        }

        // 4. Operadores con paginación — Estados AMI cargados vía AJAX (ver api.php)
        try {
            $operadoresLocales = OperadorConfig::where('is_active', true)
                ->with('extensiones')
                ->orderBy('nombre_operador')
                ->paginate(5, ['*'], 'operadores_page');

            // Cacheamos estados AMI por 10 segundos para evitar sockets bloqueantes en carga de página
            $extensionesIds = $operadoresLocales->pluck('extension')->filter()->toArray();

            $estadosRealesAmi = [];
            if ($amiConectado && !empty($extensionesIds)) {
                $cacheKey = 'dashboard:ami_statuses:' . md5(implode(',', $extensionesIds));
                $estadosRealesAmi = Cache::remember($cacheKey, 10, function () use ($extensionesIds) {
                    return $this->amiService->getExtensionsStatuses($extensionesIds);
                });
            }

            // Usamos ->through() en lugar de ->map() para mantener la paginación intacta
            $operadoresLocales->through(function ($operador) use ($extensionesPami, $estadosRealesAmi) {
                $existeEnCentral = collect($extensionesPami)->firstWhere('extension', $operador->extension);

                if ($existeEnCentral) {
                    $operador->estatus_pbx = $estadosRealesAmi[$operador->extension] ?? 'offline';
                } else {
                    $operador->estatus_pbx = 'offline';
                }

                return $operador;
            });

            $operadores = $operadoresLocales;

        } catch (\Throwable $e) {
            $operadores = collect();
            Log::error('[Dashboard] Error al mapear operadores y estados AMI: ' . $e->getMessage());
        }

        // 5. Reporte de Productividad — Cacheado 60 segundos (consultas CDR externas pesadas)
        try {
            $reporteProductividad = Cache::remember('dashboard:productividad_' . $periodo, 60, function () use ($periodo) {
                return $this->productivityService->getSummaryReport($periodo);
            });

            // Ocupación / Flujo de trabajo para ordenar (los de menor ocupación primero)
            $reporteProductividad = collect($reporteProductividad)->sortBy('ocupacion')->values()->all();
        } catch (\Throwable $e) {
            $reporteProductividad = [];
            Log::error('[Dashboard] Error al generar reporte de productividad: ' . $e->getMessage());
        }

        return [
            'amiConectado'         => $amiConectado,
            'amiMensaje'           => $amiMensaje,
            'totalOperadores'      => $totalOperadores,
            'operadoresActivos'    => $operadoresActivos,
            'totalEventosHoy'      => $totalEventosHoy,
            'erroresHoy'           => $erroresHoy,
            'ultimosEventos'       => $ultimosEventos,
            'ultimosErroresAmi'    => $ultimosErroresAmi,
            'operadores'           => $operadores,
            'reporteProductividad' => $reporteProductividad,
            'periodoFiltro'        => $periodo,
            'scheduleService'      => $this->scheduleService,
        ];
    }
}