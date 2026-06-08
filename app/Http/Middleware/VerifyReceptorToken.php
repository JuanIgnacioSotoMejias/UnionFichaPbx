<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Verifica que el request incluya el token secreto correcto.
 * Ficha debe enviar: Authorization: Bearer {RECEPTOR_API_TOKEN}
 */
class VerifyReceptorToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        $expected = config('app.receptor_api_token');

        if (empty($expected) || $token !== $expected) {
            return response()->json([
                'ok'      => false,
                'message' => 'Token de autorización inválido o ausente.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
