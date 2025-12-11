<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS Configuration
    |--------------------------------------------------------------------------
    |
    | File ini mengatur Cross-Origin Resource Sharing untuk API.
    | Setting ini bersifat "permissive" untuk memudahkan integrasi
    | sementara antar 5 kelompok.
    |
    */

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'],   // izinkan semua domain frontend kelompok lain

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false, // ubah ke true jika nanti pakai cookie auth
];
