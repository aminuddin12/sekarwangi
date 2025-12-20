<?php

namespace App\Http\Middleware;

use App\Handlers\ResponseHandler;
use App\Models\UrlApi;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class CheckApiPermission
{
    public function handle(Request $request, Closure $next)
    {
        $currentPath = '/' . $request->path();
        $method = $request->method();

        // 1. Cari konfigurasi API ini di Database (Cached)
        $apiConfig = Cache::remember("api_conf_{$method}_{$currentPath}", 3600, function () use ($currentPath, $method) {
            return UrlApi::where('url', $currentPath)
                ->where('method', $method)
                ->where('is_active', true)
                ->with('permission')
                ->first();
        });

        // Jika API tidak terdaftar di DB, apakah diblokir atau lanjut?
        // Default: Lanjut (agar route hardcoded tetap jalan), tapi bisa diubah jadi block.
        if (!$apiConfig) {
            return $next($request);
        }

        // 2. Cek Public Access
        if ($apiConfig->is_public) {
            return $next($request);
        }

        // 3. Cek Permission Spatie
        $user = Auth::user();

        if (!$user) {
            return ResponseHandler::unauthorized('Authentication required');
        }

        if ($apiConfig->permission_id && $apiConfig->permission) {
            if (!$user->hasPermissionTo($apiConfig->permission->name) && !$user->hasRole('super-admin')) {
                return ResponseHandler::error('You do not have permission to access this endpoint.', 403);
            }
        }

        return $next($request);
    }
}
