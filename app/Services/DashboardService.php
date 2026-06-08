<?php

namespace App\Services;

use App\Models\BitacoraAmi;
use App\Models\HistorialAcceso;
use App\Models\LogApiReceptor;
use App\Models\OperadorConfig;
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
     */
    public function getDashboardData(string $periodo = 'dia'): array
    {
        // 1. Health Check Activo y Datos Base desde GraphQL
        try {
            $amiConectado = $this->pbxService->checkRealtimeConnection();
            $amiMensaje   = $amiConectado
                ? 'API conectada a FreePBX'
                : 'Sin respuesta de FreePBX (timeout o sin ruta de red).';

            $extensionesPami = $amiConectado ? $this->pbxService->getOperadores() : [];
        } catch (\Throwable $e) {
            $extensionesPami = [];
            $amiConectado    = false;
            $amiMensaje      = 'Servidor PBX temporalmente fuera de línea.';

            Log::warning('[Dashboard] Error al consultar FreePBX: ' . $e->getMessage());
        }

        // 2. Métricas generales del middleware local
        try {
            $totalOperadores = OperadorConfig::count();
            $operadoresActivos = OperadorConfig::where('is_active', true)->count();
            $totalEventosHoy = HistorialAcceso::whereDate('created_at', today())->count();
            $erroresHoy = LogApiReceptor::where('codigo_respuesta', '>=', 400)
                ->whereDate('created_at', today())
                ->count();
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

        // 4. Mapeo final cruzando la existencia en la Central (GraphQL) y Estado Real-Time (AMI)
        try {
            // APLICAMOS PAGINACIÓN AQUÍ (5 por página)
            $operadoresLocales = OperadorConfig::orderByDesc('is_active')
                ->orderBy('nombre_operador')
                ->paginate(5, ['*'], 'operadores_page');

            $extensionesIds = $operadoresLocales->pluck('extension')->toArray();
            
            $estadosRealesAmi = $amiConectado ? $this->amiService->getExtensionsStatuses($extensionesIds) : [];

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

        // 5. Reporte de Productividad
        try {
            $reporteProductividad = $this->productivityService->getSummaryReport($periodo);
            
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