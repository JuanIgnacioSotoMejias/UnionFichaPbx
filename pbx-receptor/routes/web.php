<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExtensionController;
use App\Http\Controllers\OperadorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|*/

Route::get('/', function () {
    return redirect()->route('login');
});

require __DIR__.'/auth.php';

// Protected routes for authenticated users
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/pbx/status', function (\App\Services\FreePbxService $pbxService) {
        $connected = $pbxService->checkRealtimeConnection();

        return response()->json([
            'ok'         => true,
            'connected'  => $connected,
            'checked_at' => now()->toIso8601String(),
        ]);
    })->name('api.pbx.status');

    // Extensions CRUD and actions
    Route::post('extensions/sincronizar', [ExtensionController::class, 'sincronizar'])->name('extensions.sincronizar');
    Route::resource('extensions', ExtensionController::class);
    Route::post('extensions/{extension}/test', [ExtensionController::class, 'testExtension'])->name('extensions.test');
    Route::post('extensions/{extension}/liberar', [ExtensionController::class, 'liberar'])->name('extensions.liberar');
    Route::post('extensions/{extension}/enable-secure', [ExtensionController::class, 'enableSecure'])->name('extensions.enableSecure');

    // Operators CRUD and toggle
    Route::post('operadores/sincronizar-ficha', [OperadorController::class, 'sincronizarDesdeFicha'])->name('operadores.sincronizarFicha');
    Route::resource('operadores', OperadorController::class)->parameters([
        'operadores' => 'operador'
    ]);
    Route::post('operadores/{operador}/toggle', [OperadorController::class, 'toggleActivo'])->name('operadores.toggleActivo');

    // Users management
    Route::resource('users', UserController::class);

    // Reports (read‑only)
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

    // Profile routes
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Nuevo módulo de Reportes
    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('reportes/especifico', [ReporteController::class, 'especifico'])->name('reportes.especifico');
    Route::get('reportes/exportar/extension/{extension}', [ReporteController::class, 'exportarExtensionPdf'])->name('reportes.exportar.extension');
    Route::get('reportes/exportar/operador/{operador}', [ReporteController::class, 'exportarOperadorPdf'])->name('reportes.exportar.operador');
});
