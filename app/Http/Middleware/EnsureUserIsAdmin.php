<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restringe el acceso a rutas que requieren rol de administrador.
 *
 * Si el usuario autenticado no tiene el rol 'admin', se deniega el acceso
 * con una redirección al dashboard y un mensaje flash de error.
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'ok'    => false,
                    'error' => [
                        'code'    => 403,
                        'message' => 'Acceso restringido: se requieren permisos de administrador.',
                    ],
                ], Response::HTTP_FORBIDDEN);
            }

            return redirect()->route('dashboard')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
