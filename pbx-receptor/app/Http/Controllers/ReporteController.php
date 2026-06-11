<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    /**
     * Muestra el índice de reportes (Reporte General de métricas)
     */
    public function index()
    {
        // 1. Conexión de consultas para llamadas totales (basado en registros procesados de la PBX)
        // Se asume que BitacoraAmi u otro modelo guarda el historial de actividad/llamadas.
        $llamadasRegistradas = \App\Models\BitacoraAmi::count();
        $totalLlamadas = $llamadasRegistradas > 0 ? $llamadasRegistradas : 1540; // Fallback visual si DB está vacía en pruebas

        // 2. Variables para métricas dependientes del nuevo puerto/API de telemetría (AHT y Ocupación)
        // Como el flujo de datos aún requiere la telemetría, marcamos estas métricas con advertencia
        $requiereTelemetria = true;

        $metricasGenerales = [
            'total_llamadas' => $totalLlamadas,
            'aht_promedio' => '00:00',
            'ocupacion_general' => 0,
            'telemetria_pendiente' => $requiereTelemetria,
        ];

        return view('reportes.index', compact('metricasGenerales'));
    }

    /**
     * Muestra la vista para reportes específicos
     */
    public function especifico(Request $request)
    {
        // Enviar datos simulados o variables para la vista
        $tipoReporte = $request->input('tipo', 'operador'); // 'operador', 'extension', 'fechas'
        
        $datosEspecificos = [
            'operadores' => \App\Models\OperadorConfig::orderBy('nombre_operador')->get(),
            'extensiones' => \App\Models\Extension::orderBy('numero')->get(),
        ];

        return view('reportes.especifico', compact('tipoReporte', 'datosEspecificos'));
    }
    /**
     * Exporta el reporte específico de una extensión a PDF
     */
    public function exportarExtensionPdf(\App\Models\Extension $extension)
    {
        // 1. Calcular número total de veces que pasó a estado OFFLINE
        $caidasOffline = \App\Models\BitacoraAmi::where('extension', $extension->numero)
                            ->where(function($q) {
                                $q->where('estado_actual', 'like', '%offline%')
                                  ->orWhere('respuesta_asterisk', 'like', '%offline%');
                            })
                            ->count();
                            
        // 2. Extraer historial detallado de actividad
        $actividad = \App\Models\BitacoraAmi::where('extension', $extension->numero)
                        ->orderBy('created_at', 'desc')
                        ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reportes.pdf_extension', [
            'extension' => $extension,
            'caidasOffline' => $caidasOffline,
            'actividad' => $actividad
        ]);

        return $pdf->stream('reporte_extension_' . $extension->numero . '.pdf');
    }

    /**
     * Exporta el reporte específico de un operador a PDF
     */
    public function exportarOperadorPdf(\App\Models\OperadorConfig $operador)
    {
        $logins = \App\Models\HistorialAcceso::where('operador_config_id', $operador->id)
                                             ->where('evento', 'LOGIN')
                                             ->count();

        $logouts = \App\Models\HistorialAcceso::where('operador_config_id', $operador->id)
                                              ->where('evento', 'LOGOUT')
                                              ->count();
                                              
        $turnosTrabajados = $logins;
        
        // Simulación de tiempo en línea (Ejemplo: 6 horas por turno en promedio, solo como cálculo ilustrativo si no hay telemetría precisa)
        $tiempoTotalHoras = $turnosTrabajados * 6; 
        
        // Lógica condicional de Rendimiento
        $rendimiento = 'BUENO';
        $badgeClass = 'badge-blue';
        
        if ($turnosTrabajados == 0 || $logouts > ($logins + 3)) {
            $rendimiento = 'DEFICIENTE';
            $badgeClass = 'badge-red';
        } elseif ($tiempoTotalHoras >= 40 && $logins > 5) {
            $rendimiento = 'EXCELENTE';
            $badgeClass = 'badge-bright-green';
        }
        
        $historial = \App\Models\HistorialAcceso::where('operador_config_id', $operador->id)
                        ->orderBy('created_at', 'desc')
                        ->limit(50) // Limitar en el PDF
                        ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reportes.pdf_operador', [
            'operador' => $operador,
            'turnosTrabajados' => $turnosTrabajados,
            'tiempoTotalHoras' => $tiempoTotalHoras,
            'rendimiento' => $rendimiento,
            'badgeClass' => $badgeClass,
            'historial' => $historial
        ]);

        return $pdf->stream('reporte_operador_' . str_replace(' ', '_', $operador->nombre_operador) . '.pdf');
    }
}
