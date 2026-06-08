<?php

namespace App\Http\Controllers;

use App\Models\BitacoraAmi;
use App\Models\HistorialAcceso;
use App\Models\LogApiReceptor;
use App\Models\OperadorConfig;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Estado de conexión AMI
        $dryRun = config('ami.dry_run', false);
        $amiConectado = false;
        $amiMensaje   = 'Modo DRY_RUN activo';

        if (!$dryRun) {
            $socket = @fsockopen(
                config('ami.host'),
                config('ami.port'),
                $errno, $errstr,
                (int) config('ami.connect_timeout', 3)
            );
            if ($socket) {
                fclose($socket);
                $amiConectado = true;
                $amiMensaje   = 'Conectado a ' . config('ami.host') . ':' . config('ami.port');
            } else {
                $amiMensaje = "Sin conexión: {$errstr}";
            }
        }

        // Métricas generales
        $totalOperadores   = OperadorConfig::count();
        $operadoresActivos = OperadorConfig::where('is_active', true)->count();
        $totalEventosHoy   = HistorialAcceso::whereDate('created_at', today())->count();
        $erroresHoy        = LogApiReceptor::where('codigo_respuesta', '>=', 400)
                                ->whereDate('created_at', today())->count();

        // Últimas actividades
        $ultimosEventos = HistorialAcceso::with('operador')
            ->latest('created_at')
            ->take(10)
            ->get();

        // Últimos errores AMI
        $ultimosErroresAmi = BitacoraAmi::where('status', 'ERROR')
            ->latest()
            ->take(5)
            ->get();

        // Operadores con estado
        $operadores = OperadorConfig::orderByDesc('is_active')
            ->orderBy('nombre_operador')
            ->get();

        return view('dashboard', compact(
            'dryRun',
            'amiConectado',
            'amiMensaje',
            'totalOperadores',
            'operadoresActivos',
            'totalEventosHoy',
            'erroresHoy',
            'ultimosEventos',
            'ultimosErroresAmi',
            'operadores',
        ));
    }
}
