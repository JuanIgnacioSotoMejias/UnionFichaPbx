<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restringe el acceso a la API solo a las IPs configuradas.
 *
 * Soporta IPs individuales y rangos CIDR (ej: 172.16.0.0/16).
 * Si la lista está vacía, se permite el acceso desde cualquier IP (útil en desarrollo).
 */
class AllowedIpsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $allowedRaw = (string) config('app.receptor_allowed_ips', '');

        // Si no hay IPs configuradas, se permite todo (modo desarrollo)
        if (empty(trim($allowedRaw))) {
            return $next($request);
        }

        $allowedEntries = array_map('trim', explode(',', $allowedRaw));
        $clientIp = $request->ip();

        foreach ($allowedEntries as $entry) {
            if ($this->ipMatches($clientIp, $entry)) {
                return $next($request);
            }
        }

        return response()->json([
            'ok'    => false,
            'error' => [
                'code'    => 403,
                'message' => 'Acceso denegado: IP no autorizada.',
            ],
        ], Response::HTTP_FORBIDDEN);
    }

    /**
     * Verifica si una IP coincide con una entrada (IP exacta o rango CIDR).
     */
    private function ipMatches(string $clientIp, string $entry): bool
    {
        // Verificación CIDR
        if (str_contains($entry, '/')) {
            return $this->ipInCidr($clientIp, $entry);
        }

        // Verificación exacta
        return $clientIp === $entry;
    }

    /**
     * Verifica si una IP está dentro de un rango CIDR.
     */
    private function ipInCidr(string $ip, string $cidr): bool
    {
        [$subnet, $bits] = explode('/', $cidr, 2);
        $bits = (int) $bits;

        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);

        if ($ipLong === false || $subnetLong === false) {
            return false;
        }

        $mask = -1 << (32 - $bits);

        return ($ipLong & $mask) === ($subnetLong & $mask);
    }
}
