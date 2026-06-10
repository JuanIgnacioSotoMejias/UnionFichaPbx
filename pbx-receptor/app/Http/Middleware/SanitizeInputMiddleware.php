<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sanitiza todos los inputs string de la petición.
 *
 * - Aplica trim() y strip_tags() a todos los valores string.
 * - Rechaza payloads con claves potencialmente peligrosas.
 */
class SanitizeInputMiddleware
{
    /**
     * Claves de input que podrían indicar un ataque de inyección.
     */
    private const FORBIDDEN_KEYS = [
        '__proto__',
        'constructor',
        'prototype',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        // Verificar claves sospechosas
        $inputKeys = array_keys($request->all());

        foreach ($inputKeys as $key) {
            if (in_array(strtolower((string) $key), self::FORBIDDEN_KEYS, true)) {
                return response()->json([
                    'ok'    => false,
                    'error' => [
                        'code'    => 400,
                        'message' => 'Parámetro no permitido detectado.',
                    ],
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        // Sanitizar valores string
        $input = $request->all();
        $sanitized = $this->sanitizeArray($input);
        $request->merge($sanitized);

        return $next($request);
    }

    /**
     * Recorre recursivamente el array y sanitiza valores string.
     */
    private function sanitizeArray(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = strip_tags(trim($value));
            } elseif (is_array($value)) {
                $data[$key] = $this->sanitizeArray($value);
            }
        }

        return $data;
    }
}
