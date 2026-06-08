<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuración FreePBX / Asterisk AMI
    |--------------------------------------------------------------------------
    |
    | Parámetros de conexión al Asterisk Manager Interface (AMI) de FreePBX.
    | En desarrollo, activar AMI_DRY_RUN=true para simular sin conectar.
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
    | Modo Dry Run
    |--------------------------------------------------------------------------
    |
    | Si es true, las acciones AMI (QueueAdd/QueueRemove) se SIMULAN:
    | se registran en bitacora_ami con status "DRY_RUN" pero NO se envía
    | ningún comando a FreePBX. Ideal para desarrollo y pruebas.
    |
    */
    'dry_run'         => env('AMI_DRY_RUN', false),
];
