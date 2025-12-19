<?php

namespace App\Http\Middleware;

use App\Handlers\ResponseHandler;
use App\Models\ApiAccount;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiAccount
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Ambil API Key dari Header
        $apiKey = $request->header('X-API-KEY');

        if (!$apiKey) {
            return ResponseHandler::unauthorized('API Key is missing.');
        }

        // 2. Cek Cache dulu (Performance) agar tidak hit DB setiap request
        $account = Cache::remember("api_account_{$apiKey}", 3600, function () use ($apiKey) {
            return ApiAccount::where('api_key', $apiKey)
                ->where('is_active', true)
                ->first();
        });

        if (!$account) {
            return ResponseHandler::unauthorized('Invalid or inactive API Key.');
        }

        // 3. Simpan akun ke request agar bisa diakses di Controller
        $request->merge(['_api_account' => $account]);

        return $next($request);
    }
}
