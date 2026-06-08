<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Forzar uso de estilos Tailwind CSS para los enlaces de paginación
        Paginator::useTailwind();

        // 🚀 Definimos el limitador 'api' para destruir el error 500 de Laravel 11
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(env('RECEPTOR_RATE_LIMIT', 60))->by($request->ip());
        });
    }
}