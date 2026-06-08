<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restringe el acceso a la API solo a las IPs configuradas en RECEPTOR_ALLOWED_IPS.
 * Si la variable está vacía, se permite el acceso desde cualquier IP.
 */
class AllowedIpsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $allowedRaw = env('RECEPTOR_ALLOWED_IPS', '');

        // Si no hay IPs configuradas, se permite todo (útil en desarrollo)
        if (empty(trim($allowedRaw))) {
            return $next($request);
        }

        $allowedIps = array_map('trim', explode(',', $allowedRaw));
        $clientIp   = $request->ip();

        if (!in_array($clientIp, $allowedIps, true)) {
            return response()->json([
                'ok'      => false,
                'message' => 'Acceso denegado: IP no autorizada.',
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
