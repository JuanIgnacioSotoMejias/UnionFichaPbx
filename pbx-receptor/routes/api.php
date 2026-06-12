<?php

use App\Http\Controllers\Api\TelefoniaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API del Sistema Receptor PBX
|--------------------------------------------------------------------------
|
| Todas las rutas están protegidas por:
|   1. force.json     — fuerza respuestas JSON
|   2. sanitize.input — sanitiza inputs contra inyección
|   3. receptor.ips   — whitelist de IPs (configurable en .env)
|   4. receptor.token — Bearer token compartido con el sistema Ficha
|   5. throttle       — rate limiting (configurable en .env)
|
| Endpoint público (health-check sin auth):
|   GET /api/ping
|
*/

// Health-check público — útil para monitoreo externo
Route::get('/ping', fn () => response()->json(['ok' => true, 'service' => 'pbx-receptor']));

/*
|--------------------------------------------------------------------------
| Estado de conectividad FreePBX (Health Check Activo)
|--------------------------------------------------------------------------
| GET /api/pbx/status
|
| Protegido por auth:sanctum (funciona con sesiones Breeze vía cookie).
| Devuelve JSON { ok, connected, checked_at } invocando el Health Check
| Activo (Heartbeat) para que el frontend pueda consultarlo vía setInterval
| y actualizar el indicador de estado en tiempo real sin recargar la página.
|
*/

// Rutas protegidas con seguridad completa
Route::middleware([
    'force.json',
    'sanitize.input',
    'receptor.ips',
    'receptor.token',
    'throttle:' . env('RECEPTOR_RATE_LIMIT', 60) . ',1',
])->group(function () {

    // Heartbeat endpoint for active operator sessions
    Route::post('/heartbeat', [\App\Http\Controllers\Api\TelefoniaController::class, 'heartbeat'])->name('api.heartbeat');

    // Log endpoint for API error reporting
    Route::post('/log', [\App\Http\Controllers\Api\LogController::class, 'store'])->name('api.log');

    // Estado del servicio y conexión AMI
    Route::get('/status', [TelefoniaController::class, 'status'])->name('api.status');

    // Lista de operadores registrados
    Route::get('/operadores', [TelefoniaController::class, 'listarOperadores'])->name('api.operadores');

    // Estado específico de un operador y su extensión en el PBX
    Route::post('/operadores/estado', [TelefoniaController::class, 'estadoOperador'])->name('api.operadores.estado');

    // Evento principal: LOGIN / LOGOUT desde el sistema Ficha
    Route::post('/sesion', [TelefoniaController::class, 'sesion'])->name('api.sesion');

    // Cambiar la extensión de un operador en caliente
    Route::post('/operadores/{id}/cambiar-extension', [TelefoniaController::class, 'cambiarExtension'])->name('api.cambiar-extension');

    // Ver estado de una cola (llamadas en espera)
    Route::get('/cola/{queue}/status', [TelefoniaController::class, 'statusCola'])->name('api.cola.status');
});