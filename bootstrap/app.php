<?php

use App\Http\Middleware\AllowedIpsMiddleware;
use App\Http\Middleware\VerifyReceptorToken;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Alias de middlewares para uso en rutas
        $middleware->alias([
            'receptor.token'    => VerifyReceptorToken::class,
            'receptor.ips'      => AllowedIpsMiddleware::class,
        ]);

        // Rate limiting para la API del receptor
        $middleware->throttleApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
