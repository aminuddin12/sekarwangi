<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class RedisService
{
    /**
     * Simpan data ke Redis dengan waktu kadaluarsa (TTL)
     */
    public function set(string $key, $value, int $ttlSeconds = 3600): void
    {
        // Serialize jika array/object agar data utuh
        $content = is_array($value) || is_object($value) ? json_encode($value) : $value;

        Redis::setex($key, $ttlSeconds, $content);
    }

    /**
     * Ambil data dari Redis
     */
    public function get(string $key, $default = null)
    {
        $value = Redis::get($key);

        if (!$value) {
            return $default;
        }

        // Coba decode JSON, jika gagal kembalikan string asli
        $decoded = json_decode($value, true);
        return (json_last_error() === JSON_ERROR_NONE) ? $decoded : $value;
    }

    /**
     * Hapus data
     */
    public function delete(string $key): void
    {
        Redis::del($key);
    }

    /**
     * Cek keberadaan key
     */
    public function exists(string $key): bool
    {
        return (bool) Redis::exists($key);
    }

    /**
     * Increment (Untuk Rate Limiting)
     */
    public function increment(string $key): int
    {
        return Redis::incr($key);
    }

    /**
     * Blacklist IP/Device (Set Permanent)
     */
    public function blacklist(string $identifier, string $reason, int $duration = 86400): void
    {
        $this->set("blacklist:{$identifier}", [
            'reason' => $reason,
            'blocked_at' => now()->toIso8601String()
        ], $duration);
    }

    /**
     * Cek apakah IP/Device di-blacklist
     */
    public function isBlacklisted(string $identifier): bool
    {
        return $this->exists("blacklist:{$identifier}");
    }
}
