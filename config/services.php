<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /* Configuración para la conexión con la Central FreePBX */
    'freepbx' => [
        'url'                  => env('FREEPBX_URL'),
        'api_token'            => env('FREEPBX_API_TOKEN'),
        'client_id'            => env('FREEPBX_CLIENT_ID'),
        'client_secret'        => env('FREEPBX_CLIENT_SECRET'),
        'token_path'           => env('FREEPBX_TOKEN_PATH', '/admin/api/api/token'),
        'graphql_path'         => env('FREEPBX_GRAPHQL_PATH', '/admin/api/api/gql'),
        'health_check_timeout' => (int) env('FREEPBX_HEALTH_CHECK_TIMEOUT', 10),
    ],
];