<?php

namespace App\Servicios;

/**
 * Servicio Cliente HTTP para la comunicación con el PBX Receptor.
 */
class PbxApiService {
    private array $config;

    public function __construct() {
        $this->config = require 'app/Config/pbx_config.php';
    }

    /**
     * Envía una petición POST al PBX Receptor usando cURL.
     * Implementa timeouts bajos y manejo silencioso de errores (no bloqueante).
     *
     * @param string $endpoint
     * @param array $payload
     * @return array
     */
    private function enviarPeticion(string $endpoint, array $payload): array {
        if (!$this->config['enabled']) {
            return ['success' => false, 'error' => 'Integración PBX deshabilitada en configuración.'];
        }

        $url = rtrim($this->config['base_url'], '/') . '/' . ltrim($endpoint, '/');
        $jsonPayload = json_encode($payload);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->config['timeout']);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->config['timeout']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $this->config['token']
        ]);

        // Deshabilitar verificación SSL dado que son entornos locales/IPs de red privada
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            $errorMsg = "Error cURL conectando a PBX ({$url}): {$curlError}";
            error_log($errorMsg);
            return ['success' => false, 'error' => $errorMsg];
        }

        $decoded = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $errorMsg = "Respuesta no JSON de PBX ({$url}): " . substr($response, 0, 200);
            error_log($errorMsg);
            return ['success' => false, 'error' => $errorMsg];
        }

        if ($httpCode >= 400) {
            $errorMsg = $decoded['error']['message'] ?? $decoded['message'] ?? "Código HTTP {$httpCode}";
            error_log("PBX API retornó error ({$url}) [HTTP {$httpCode}]: {$errorMsg}");
            return ['success' => false, 'error' => $errorMsg, 'http_code' => $httpCode];
        }

        return ['success' => true, 'data' => $decoded];
    }

    /**
     * Notifica el inicio de sesión del operador al PBX.
     *
     * @param string $usuario Nombre de usuario en el sistema Ficha (ficha_username).
     * @param string $nombre Nombre completo del operador.
     * @param string $cedula Cédula de identidad del operador.
     * @return array
     */
    public function notificarLogin(string $usuario, string $nombre, string $cedula = ''): array {
        return $this->enviarPeticion('sesion', [
            'usuario' => $usuario,
            'evento'  => 'LOGIN',
            'nombre'  => $nombre,
            'cedula'  => $cedula,
            'cola'    => $this->config['cola_default']
        ]);
    }

    /**
     * Notifica el cierre de sesión del operador al PBX.
     *
     * @param string $usuario Nombre de usuario en el sistema Ficha.
     * @return array
     */
    public function notificarLogout(string $usuario): array {
        return $this->enviarPeticion('sesion', [
            'usuario' => $usuario,
            'evento'  => 'LOGOUT'
        ]);
    }

    /**
     * Envía un ping de heartbeat para mantener activa la sesión en el PBX.
     *
     * @param string $usuario Nombre de usuario en el sistema Ficha.
     * @return array
     */
    public function enviarHeartbeat(string $usuario): array {
        return $this->enviarPeticion('heartbeat', [
            'ficha_username' => $usuario
        ]);
    }

    /**
     * Obtiene el estado del operador y de su teléfono desde el PBX.
     *
     * @param string $usuario Nombre de usuario en el sistema Ficha.
     * @return array
     */
    public function obtenerEstadoOperador(string $usuario): array {
        return $this->enviarPeticion('operadores/estado', [
            'ficha_username' => $usuario
        ]);
    }

    /**
     * Registra un log remoto en el PBX Receptor.
     *
     * @param string $usuario Nombre de usuario.
     * @param string $nivel Nivel de log (info, warning, error, etc.).
     * @param string $mensaje
     * @param array $contexto
     * @return array
     */
    public function registrarLog(string $usuario, string $nivel, string $mensaje, array $contexto = []): array {
        $contexto['ficha_username'] = $usuario;
        return $this->enviarPeticion('log', [
            'level'   => $nivel,
            'message' => $mensaje,
            'context' => $contexto
        ]);
    }
}
