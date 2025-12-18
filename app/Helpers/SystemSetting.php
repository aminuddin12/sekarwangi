<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SystemSetting
{
    /**
     * Ambil Value Setting berdasarkan Key
     * Menggunakan Cache selama 24 jam untuk performa
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting_{$key}", 60 * 60 * 24, function () use ($key, $default) {
            $setting = Setting::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Paksa Refresh Cache Setting (Panggil ini setelah update setting)
     */
    public static function refresh(string $key): void
    {
        Cache::forget("setting_{$key}");
    }
}
