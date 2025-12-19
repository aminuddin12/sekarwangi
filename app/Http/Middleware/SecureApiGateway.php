<?php

namespace App\Http\Middleware;

use App\Handlers\ResponseHandler;
use App\Helpers\RedisHelper;
use App\Services\RedisService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecureApiGateway
{
    protected $redis;

    public function __construct(RedisService $redis)
    {
        $this->redis = $redis;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent() ?? 'unknown_device';
        $deviceHash = md5($ip . $userAgent); // Fingerprint sederhana

        // 1. Cek Blacklist (Level 1: IP & Device)
        if ($this->redis->isBlacklisted($ip)) {
            return ResponseHandler::error('Your IP address has been blocked due to suspicious activity.', 403);
        }

        if ($this->redis->isBlacklisted($deviceHash)) {
            return ResponseHandler::error('Your device has been blocked.', 403);
        }

        // 2. Rate Limiting Ketat (Level 2)
        // Maksimal 60 request per menit per IP
        $key = "rate_limit:{$ip}";
        $currentHits = $this->redis->increment($key);

        if ($currentHits === 1) {
            $this->redis->set($key, 1, 60); // Reset dalam 60 detik
        }

        if ($currentHits > 100) { // Jika spam > 100 hit/menit -> AUTO BLOCK
            $this->redis->blacklist($ip, 'Rate limit exceeded (DDoS Protection)', 3600); // Block 1 jam
            return ResponseHandler::error('Too many requests. You have been temporarily blocked.', 429);
        }

        if ($currentHits > 60) {
            return ResponseHandler::error('Too many requests. Please slow down.', 429);
        }

        return $next($request);
    }
}
