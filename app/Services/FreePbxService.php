<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class FreePbxService
{
    private const CACHE_KEY = 'freepbx_bearer_token';
    private const SAFETY_MARGIN = 300;

    private string $baseUrl;
    private string $graphqlUrl;
    private bool $dryRun;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.freepbx.url'), '/');
        // Revertido a la versión segura: Construimos la URL GraphQL basándonos
        // estrictamente en el baseUrl, garantizando el protocolo HTTPS.
        $this->graphqlUrl = $this->baseUrl . config('services.freepbx.graphql_path', '/admin/api/api/gql');
        
        // 🚀 Detectamos si el sistema está operando en modo simulación local
        $this->dryRun = (bool) env('AMI_DRY_RUN', false);
    }

    public function getValidToken(): string
    {
        if ($this->dryRun) {
            return 'dry_run_simulated_token';
        }
        return Cache::get(self::CACHE_KEY) ?? $this->requestNewToken();
    }

    public function refreshToken(): string
    {
        Cache::forget(self::CACHE_KEY);
        return $this->requestNewToken();
    }

    public function executeGraphqlQuery(string $query, array $variables = []): array
    {
        if ($this->dryRun) {
            return [];
        }

        $payload = array_filter(['query' => $query, 'variables' => $variables]);
        $response = $this->sendGraphqlRequest($this->graphqlUrl, $payload);

        if ($response->status() === 401) {
            Log::warning('[FreePBX] Token rechazado (401). Renovando token...');
            $this->refreshToken();
            $response = $this->sendGraphqlRequest($this->graphqlUrl, $payload);
        }

        if ($response->failed()) {
            Log::error("[FreePBX] Fallo GraphQL ({$response->status()}): {$response->body()}");
            throw new RuntimeException("Error en API GraphQL de FreePBX ({$response->status()}).");
        }

        return $response->json('data') ?? [];
    }

    public function getOperadores(): array
    {
        // 🚀 Si estamos simulando, devolvemos una lista vacía controlada de inmediato
        if ($this->dryRun) {
            return [];
        }

        try {
            // El campo 'status' actualmente lanza un Internal Server Error (HTTP 500) en FreePBX.
            // Por lo tanto, solicitamos solo extensionId y user { name } para que la consulta funcione.
            $query = '{ fetchAllExtensions { extension { extensionId user { name } } } }';
            $data = $this->executeGraphqlQuery($query);

            $extensions = $data['fetchAllExtensions']['extension'] ?? [];

            // Mapeamos los resultados para que el DashboardController los consuma fácilmente
            return array_map(function ($ext) {
                return [
                    'extension' => $ext['extensionId'] ?? '',
                    'name'      => $ext['user']['name'] ?? '',
                    'status'    => 'unknown' // Status omitido temporalmente por falla en la API de FreePBX
                ];
            }, $extensions);
        } catch (Throwable $e) {
            Log::error('[FreePBX] Error al obtener operadores: ' . $e->getMessage());
            return [];
        }
    }

    public function checkRealtimeConnection(): bool
    {
        // 🚀 SI EL MODO SIMULACIÓN ESTÁ ACTIVO, EVITA ENTRAR EN CURL Y RESPONDE VERDADERO
        if ($this->dryRun) {
            return true;
        }

        // Si tenemos un registro en cache de que está offline, evitamos hacer la petición real
        if (Cache::has('freepbx_offline_since')) {
            return false;
        }

        $timeout = (int) config('services.freepbx.health_check_timeout', 3);
        
        try {
            // Consulta validada que sí responde HTTP 200 en tu central
            $response = Http::withoutVerifying()
                ->withToken($this->getValidToken())
                ->timeout($timeout)
                ->post($this->graphqlUrl, [
                    'query' => '{ fetchAllExtensions { extension { extensionId } } }'
                ]);

            if ($response->successful()) {
                // Si la conexión es exitosa, nos aseguramos de borrar el estado de offline
                Cache::forget('freepbx_offline_since');
                return true;
            }

            // Si falla la petición HTTP, marcar como offline por 3600 segundos (para no saturar a FreePBX)
            Cache::put('freepbx_offline_since', now()->timestamp, 3600);
            return false;
        } catch (Throwable $e) {
            // Si hay una excepción, marcar como offline por 3600 segundos
            Cache::put('freepbx_offline_since', now()->timestamp, 3600);
            
            // Throttle de 60 segundos para evitar spam de warnings
            if (!Cache::has('freepbx_log_throttle')) {
                Log::warning('[FreePBX] Excepción de red en Health Check HTTPS: ' . $e->getMessage());
                Cache::put('freepbx_log_throttle', true, 60);
            }
            return false;
        }
    }

    /**
     * Obtiene TODAS las extensiones de la central FreePBX con datos detallados.
     * Se usa para sincronizar la tabla local `extensions` con la central.
     *
     * Retorna array de:
     *  ['extension' => '8001', 'name' => 'Operador 1', 'tech' => 'pjsip']
     */
    public function fetchAllExtensionsDetailed(): array
    {
        if ($this->dryRun) {
            // Simulación: devolver 27 extensiones dummy como en FreePBX
            $extensions = [];
            for ($i = 1; $i <= 27; $i++) {
                $extensions[] = [
                    'extension' => (string)(8000 + $i),
                    'name'      => "Operador {$i}",
                    'tech'      => 'pjsip',
                ];
            }
            return $extensions;
        }

        try {
            $query = '{ fetchAllExtensions { extension { extensionId user { name } } } }';
            $data = $this->executeGraphqlQuery($query);

            $extensions = $data['fetchAllExtensions']['extension'] ?? [];

            return array_map(function ($ext) {
                return [
                    'extension' => $ext['extensionId'] ?? '',
                    'name'      => $ext['user']['name'] ?? '',
                    'tech'      => 'pjsip', // Todas las extensiones en esta central son PJSIP
                ];
            }, $extensions);
        } catch (Throwable $e) {
            Log::error('[FreePBX] Error al obtener extensiones detalladas: ' . $e->getMessage());
            return [];
        }
    }

    public function checkConnection(): array
    {
        $connected = $this->checkRealtimeConnection();
        return [
            'connected' => $connected,
            'message'   => $connected 
                ? "API GraphQL conectada exitosamente mediante HTTPS (Simulado o Real)" 
                : "Sin respuesta segura de FreePBX en {$this->graphqlUrl} (verificar SSL/red).",
        ];
    }

    private function requestNewToken(): string
    {
        $tokenUrl = $this->baseUrl . config('services.freepbx.token_path', '/admin/api/api/token');

        try {
            $response = Http::asForm()
                ->withoutVerifying()
                ->timeout(10)
                ->post($tokenUrl, [
                    'grant_type'    => 'client_credentials',
                    'client_id'     => config('services.freepbx.client_id'),
                    'client_secret' => config('services.freepbx.client_secret'),
                ]);
        } catch (Throwable $e) {
            Log::error('[FreePBX] Error de red al solicitar token HTTPS: ' . $e->getMessage());
            throw new RuntimeException('Error de red al conectar con FreePBX por token.');
        }

        if ($response->failed()) {
            Log::error("[FreePBX] Error de token ({$response->status()}): " . $response->body());
            throw new RuntimeException("Error al obtener token ({$response->status()}).");
        }

        $data = $response->json();
        $token = $data['access_token'] ?? null;
        $expiresIn = (int) ($data['expires_in'] ?? 3600);

        if (empty($token)) {
            throw new RuntimeException('Respuesta de token no contiene access_token.');
        }

        $ttl = max($expiresIn - self::SAFETY_MARGIN, 60);
        Cache::put(self::CACHE_KEY, $token, $ttl);

        Log::info("[FreePBX] Token HTTPS renovado con éxito. TTL: {$ttl}s.");

        return $token;
    }

    private function sendGraphqlRequest(string $url, array $payload): \Illuminate\Http\Client\Response
    {
        return Http::withoutVerifying()
            ->withToken($this->getValidToken())
            ->timeout(15)
            ->post($url, $payload);
    }
}