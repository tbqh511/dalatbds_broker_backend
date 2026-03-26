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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google_maps' => [
        'place_api_key' => env('PLACE_API_KEY'),
        // Key used specifically for web app (picker). Falls back to PLACE_API_KEY when not set.
        'place_api_key_web' => env('PLACE_API_KEY_WEB_APP', env('PLACE_API_KEY')),
    ],

    'telegram' => [
        'bot_token'      => env('TELEGRAM_BOT_TOKEN'),
        'webhook_secret' => env('TELEGRAM_WEBHOOK_SECRET'),
        'bot_username'   => env('TELEGRAM_BOT_USERNAME', 'dalatbds_telegram_bot'),
        'webapp_short_name' => env('TELEGRAM_WEBAPP_SHORT_NAME', 'dangtin'),
        'groups' => [
            'public_channel' => env('TELEGRAM_PUBLIC_CHANNEL_ID'),
            'sale_admin'     => env('TELEGRAM_SALE_ADMIN_GROUP_ID'),
            'bds_admin'      => env('TELEGRAM_BDS_ADMIN_GROUP_ID'),
        ],
    ],

];
