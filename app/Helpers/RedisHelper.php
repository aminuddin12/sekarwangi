<?php

namespace App\Helpers;

use App\Services\RedisService;

class RedisHelper
{
    protected static $service;

    protected static function getService(): RedisService
    {
        if (!self::$service) {
            self::$service = new RedisService();
        }
        return self::$service;
    }

    public static function set(string $key, $value, int $ttlSeconds = 3600): void
    {
        self::getService()->set($key, $value, $ttlSeconds);
    }

    public static function get(string $key, $default = null)
    {
        return self::getService()->get($key, $default);
    }

    public static function forget(string $key): void
    {
        self::getService()->delete($key);
    }

    // Fitur Keamanan
    public static function banIp(string $ip, string $reason = 'Suspicious Activity'): void
    {
        self::getService()->blacklist($ip, $reason);
    }

    public static function isBanned(string $ip): bool
    {
        return self::getService()->isBlacklisted($ip);
    }
}
