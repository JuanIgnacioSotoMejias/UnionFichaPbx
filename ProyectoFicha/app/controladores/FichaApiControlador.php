<?php
/**
 * CONTROLADOR: FichaApiControlador
 * Propósito: Proveer un endpoint seguro para que pbx-receptor consulte operadores.
 */

require_once 'app/modelos/UsuarioModelo.php';

use App\modelos\UsuarioModelo;

class FichaApiControlador {

    private array $config;

    public function __construct() {
        // Cargar la configuración de la integración PBX que contiene el token Bearer compartido
        $this->config = require 'app/Config/pbx_config.php';
    }

    /**
     * Valida el Bearer Token en la cabecera Authorization.
     */
    private function validarAutorizacion(): bool {
        // Si la integración está deshabilitada en la configuración, bloquear acceso
        if (!$this->config['enabled']) {
            return false;
        }

        $authHeader = '';
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $authHeader = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        } elseif (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            if (isset($headers['Authorization'])) {
                $authHeader = $headers['Authorization'];
            }
        }

        if (!$authHeader || !preg_match('/Bearer\s(\S+)/i', $authHeader, $matches)) {
            return false;
        }

        return hash_equals($this->config['token'], $matches[1]);
    }

    /**
     * Endpoint: GET index.php?url=fichaApi/operadores
     */
    public function operadores() {
        header('Content-Type: application/json');

        if (!$this->validarAutorizacion()) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Token de autorización inválido o ausente.'
            ]);
            return;
        }

        try {
            $modelo = new UsuarioModelo();
            $operadores = $modelo->obtenerOperadoresActivos();

            echo json_encode([
                'success' => true,
                'data' => $operadores
            ]);
        } catch (\Throwable $e) {
            error_log("[FichaApiControlador] Error en operadores: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Ocurrió un error interno en el servidor.'
            ]);
        }
    }
}
