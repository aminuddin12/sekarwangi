<?php

namespace App\Generator;

use Illuminate\Support\Str;

class ApiSecretGenerator
{
    /**
     * Generate Secret API Key
     * Format: sk_live_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
     */
    public static function generate(string $environment = 'live'): string
    {
        $prefix = $environment === 'live' ? 'sk_live_' : 'sk_test_';

        // Secret key lebih panjang (64 karakter) untuk entropi lebih tinggi
        return $prefix . Str::random(64);
    }
}
