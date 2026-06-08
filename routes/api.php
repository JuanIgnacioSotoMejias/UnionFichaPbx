<?php

use App\Http\Controllers\Api\TelefoniaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API del Sistema Receptor PBX
|--------------------------------------------------------------------------
|
| Todas las rutas están protegidas por:
|   1. receptor.ips   — whitelist de IPs (configurable en .env)
|   2. receptor.token — Bearer token compartido con el sistema Ficha
|   3. throttle       — rate limiting (configurable en .env)
|
| Endpoint público (health-check sin auth):
|   GET /api/ping
|
*/

// Health-check público — útil para monitoreo externo
Route::get('/ping', fn () => response()->json(['ok' => true, 'service' => 'pbx-receptor']));

// Rutas protegidas con seguridad completa
Route::middleware([
    'receptor.ips',
    'receptor.token',
    'throttle:' . env('RECEPTOR_RATE_LIMIT', 60) . ',1',
])->group(function () {

    // Estado del servicio y conexión AMI
    Route::get('/status', [TelefoniaController::class, 'status'])->name('api.status');

    // Lista de operadores registrados
    Route::get('/operadores', [TelefoniaController::class, 'listarOperadores'])->name('api.operadores');

    // Evento principal: LOGIN / LOGOUT desde el sistema Ficha
    Route::post('/sesion', [TelefoniaController::class, 'sesion'])->name('api.sesion');
});
