<?php

use App\Http\Middleware\AllowedIpsMiddleware;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\ForceJsonResponseMiddleware;
use App\Http\Middleware\SanitizeInputMiddleware;
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
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Alias de middlewares para uso en rutas
        $middleware->alias([
            'receptor.token'  => VerifyReceptorToken::class,
            'receptor.ips'    => AllowedIpsMiddleware::class,
            'force.json'      => ForceJsonResponseMiddleware::class,
            'sanitize.input'  => SanitizeInputMiddleware::class,
            'admin'           => EnsureUserIsAdmin::class,
        ]);

        // Rate limiting para la API del receptor
        $middleware->throttleApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Retornar JSON consistente para errores en rutas API
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                $status = method_exists($e, 'getStatusCode')
                    ? $e->getStatusCode()
                    : 500;

                $message = match (true) {
                    $e instanceof \Illuminate\Validation\ValidationException => 'Error de validación.',
                    $e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException => 'Recurso no encontrado.',
                    $e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException => 'Método HTTP no permitido.',
                    $e instanceof \Illuminate\Auth\AuthenticationException => 'No autenticado.',
                    default => app()->hasDebugModeEnabled()
                        ? $e->getMessage()
                        : 'Error interno del servidor.',
                };

                $response = [
                    'ok'    => false,
                    'error' => [
                        'code'    => $status,
                        'message' => $message,
                    ],
                ];

                // Incluir errores de validación detallados
                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    $response['error']['details'] = $e->errors();
                    $status = 422;
                }

                return response()->json($response, $status);
            }

            return null;
        });
    })->create();
