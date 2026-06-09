<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Models\User;

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

        // 🚀 Gate para controlar acceso basado en Roles (RBAC)
        Gate::define('manage-system', function (User $user) {
            return $user->isAdmin();
        });

        // 🚀 Auditoría de Sesiones
        Event::listen(Login::class, function (Login $event) {
            $event->user->update([
                'last_login_at' => now(),
                'last_login_ip' => request()->ip(),
            ]);
        });

        Event::listen(Logout::class, function (Logout $event) {
            if ($event->user) {
                $event->user->update([
                    'last_logout_at' => now(),
                ]);
            }
        });
    }
}