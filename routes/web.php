<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OperadorController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Gestión de operadores (sólo admin TI)
    Route::resource('/operadores', OperadorController::class);
    Route::post('/operadores/{operador}/toggle', [OperadorController::class, 'toggleActivo'])
        ->name('operadores.toggle');
});

require __DIR__.'/auth.php';
