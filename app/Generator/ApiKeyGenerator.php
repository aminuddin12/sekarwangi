<?php

namespace App\Generator;

use Illuminate\Support\Str;

class ApiKeyGenerator
{
    /**
     * Generate Public API Key
     * Format: pk_live_xxxxxxxxxxxxxxxx
     */
    public static function generate(string $environment = 'live'): string
    {
        $prefix = $environment === 'live' ? 'pk_live_' : 'pk_test_';

        // Menggunakan random string yang aman (cryptographically secure)
        return $prefix . Str::random(32);
    }
}
