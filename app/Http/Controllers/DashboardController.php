<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Carga el panel de control principal cruzando datos locales y remotos a través del servicio.
     */
    public function index(DashboardService $dashboardService, \App\Services\TelemetryService $telemetryService): View
    {
        $data = $dashboardService->getDashboardData();

        // 1. Obtener la extensión y el ID local dinámicamente del operador autenticado
        $user = \Illuminate\Support\Facades\Auth::user();
        $extension = $user->operador->extension ?? null; 
        $operadorId = $user->operador->id ?? $user->id; 

        // Variables iniciales
        $aht = 0;
        $ocupacion = 0.0;

        // 2. Si el usuario tiene una extensión asignada, extraemos telemetría
        if ($extension) {
            $aht = $telemetryService->getOperatorAHT($extension);
            $ocupacion = $telemetryService->getOperatorOccupation($extension, $operadorId);
        }

        // 3. Definir estilos institucionales dinámicamente
        if ($ocupacion < 70) {
            $colorOcupacion = 'text-slate-400';
            $bgOcupacion = 'bg-slate-300';
        } elseif ($ocupacion >= 70 && $ocupacion <= 95) {
            $colorOcupacion = 'text-emerald-600';
            $bgOcupacion = 'bg-emerald-500';
        } else {
            $colorOcupacion = 'text-red-500';
            $bgOcupacion = 'bg-red-500';
        }

        $data = array_merge($data, compact(
            'aht', 
            'ocupacion', 
            'colorOcupacion', 
            'bgOcupacion', 
            'extension'
        ));

        return view('dashboard', $data);
    }
}
