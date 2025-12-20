<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Access & Security Configuration
    |--------------------------------------------------------------------------
    |
    | Pengaturan ini mengontrol bagaimana API Anda diakses dan diamankan.
    | Pastikan konfigurasi ini sesuai dengan kebutuhan produksi.
    |
    */

    'security' => [
        // Kunci master enkripsi untuk API (Gunakan APP_KEY jika null)
        'encryption_key' => env('API_ENCRYPTION_KEY', env('APP_KEY')),

        // Paksa HTTPS untuk semua request API
        'force_https' => env('API_FORCE_HTTPS', true),

        // Strict Mode: Tolak request tanpa User-Agent atau dari tools mencurigakan
        'strict_mode' => env('API_STRICT_MODE', true),

        // Validasi Signature untuk Request Penting (Mencegah Tampering)
        'verify_signature' => env('API_VERIFY_SIGNATURE', true),
        'signature_header' => 'X-Signature',
        'timestamp_header' => 'X-Timestamp',
        'tolerance_seconds' => 300, // Request kadaluarsa dalam 5 menit
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting (Throttling)
    |--------------------------------------------------------------------------
    |
    | Mencegah Abuse/DDoS dengan membatasi jumlah request.
    | Menggunakan Redis sebagai backend storage.
    |
    */

    'rate_limiting' => [
        'enabled' => true,
        'driver' => 'redis', // Wajib redis untuk performa tinggi
        'cache_prefix' => 'api_rate_limit:',

        'limits' => [
            // Tamu / Tanpa Login (Berdasarkan IP)
            'guest' => [
                'limit' => 60,
                'period' => 1, // menit
            ],
            // User Terdaftar (Berdasarkan User ID)
            'authenticated' => [
                'limit' => 120,
                'period' => 1,
            ],
            // API Khusus (Misal: Partner Integrasi)
            'partner' => [
                'limit' => 1000,
                'period' => 1,
            ],
        ],

        // Durasi Ban otomatis jika melanggar limit berulang kali
        'ban_duration' => 3600, // 1 jam
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication & Authorization
    |--------------------------------------------------------------------------
    */

    'auth' => [
        // Driver default untuk token API (Sanctum / Passport)
        'driver' => 'sanctum',

        // Masa berlaku Token (dalam menit)
        'token_expiration' => env('API_TOKEN_EXPIRATION', 60 * 24 * 7), // 1 Minggu

        // Header wajib untuk API Key
        'key_header' => 'X-API-KEY',
        'secret_header' => 'X-API-SECRET',
    ],

    /*
    |--------------------------------------------------------------------------
    | Response Formatting
    |--------------------------------------------------------------------------
    */

    'response' => [
        // Selalu kembalikan JSON (bahkan jika error 500)
        'always_json' => true,

        // Sertakan Execution Time di header response
        'include_exec_time' => true,

        // Format tanggal default di response JSON
        'date_format' => 'Y-m-d\TH:i:s\Z', // ISO 8601
    ],

    /*
    |--------------------------------------------------------------------------
    | CORS & Headers (Cross-Origin Resource Sharing)
    |--------------------------------------------------------------------------
    */

    'headers' => [
        'Access-Control-Allow-Origin' => env('FRONTEND_URL', '*'),
        'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type, X-API-KEY, Authorization, X-Requested-With',
        'X-Content-Type-Options' => 'nosniff',
        'X-Frame-Options' => 'DENY',
        'X-XSS-Protection' => '1; mode=block',
    ],

    /*
    |--------------------------------------------------------------------------
    | Documentation
    |--------------------------------------------------------------------------
    */
    'documentation' => [
        'url' => env('APP_URL') . '/docs/api',
        'version' => 'v1',
    ],
];
