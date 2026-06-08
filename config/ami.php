<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuración FreePBX / Asterisk AMI
    |--------------------------------------------------------------------------
    |
    | Parámetros de conexión al Asterisk Manager Interface (AMI) de FreePBX.
    |
    */

    'host'            => env('ASTERISK_HOST', '127.0.0.1'),
    'port'            => (int) env('ASTERISK_PORT', 5038),
    'user'            => env('ASTERISK_USER', ''),
    'secret'          => env('ASTERISK_SECRET', ''),
    'connect_timeout' => (int) env('ASTERISK_CONNECT_TIMEOUT', 3),
    'read_timeout'    => (int) env('ASTERISK_READ_TIMEOUT', 3),

    /*
    |--------------------------------------------------------------------------
    | Modo Dry Run (Simulación)
    |--------------------------------------------------------------------------
    |
    | filter_var asegura que si en el .env pones "false" o "true" como texto,
    | PHP lo interprete estrictamente como un booleano real.
    |
    */
    'dry_run'         => filter_var(env('AMI_DRY_RUN', false), FILTER_VALIDATE_BOOLEAN),

    /*
    |--------------------------------------------------------------------------
    | Umbral de Calidad (Productividad)
    |--------------------------------------------------------------------------
    */
    'short_call_threshold' => (int) env('AMI_SHORT_CALL_THRESHOLD', 10),
];