<?php

return [
    'prefix' => env('REDIS_PREFIX', 'sekarwangi_app_'),

    'keys' => [
        'user_session' => 'session:user:',
        'api_throttle' => 'throttle:api:',
        'otp' => 'auth:otp:',
        'cache_menu' => 'cache:menu:',
        'online_users' => 'stats:online_users',
    ],

    'ttl' => [
        'otp' => 300, // 5 menit
        'menu' => 3600, // 1 jam
        'short_cache' => 60, // 1 menit
        'long_cache' => 86400, // 24 jam
    ],
];
