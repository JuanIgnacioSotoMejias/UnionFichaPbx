<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Fuerza que todas las peticiones API acepten JSON.
 *
 * Esto garantiza que los errores de validación y excepciones
 * de Laravel retornen respuestas JSON en lugar de redirecciones HTML.
 */
class ForceJsonResponseMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
