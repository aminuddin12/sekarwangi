<?php

namespace App\Helpers;

use App\Models\UrlAuthenticated;
use App\Models\UrlPublic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class UrlManager
{
    /**
     * Dapatkan Menu Sidebar User yang Sedang Login
     * (Filtered by Permission & Cached)
     */
    public static function getSidebarMenu()
    {
        $user = Auth::user();
        if (!$user) return [];

        // Cache Key unik per User ID dan Role-nya
        // Kita gunakan key sederhana tanpa tags agar kompatibel dengan driver 'file'
        $cacheKey = self::getMenuCacheKey($user->id);

        return Cache::remember($cacheKey, 3600, function () use ($user) {

            // 1. Ambil semua menu aktif, urutkan berdasarkan order
            $menus = UrlAuthenticated::where('is_active', true)
                ->whereNull('parent_id') // Ambil root menu dulu
                ->with(['children' => function($q) {
                    $q->where('is_active', true)->orderBy('order');
                }, 'permission'])
                ->orderBy('order')
                ->get();

            // 2. Filter & Transformasi URL
            return $menus->filter(function ($menu) use ($user) {
                return self::userCanAccessMenu($user, $menu);
            })->map(function ($menu) use ($user) {
                // Resolve URL Parent
                $menu->url = self::resolveUrl($menu->url);

                // Filter & Resolve Children
                $menu->children = $menu->children->filter(function ($child) use ($user) {
                    return self::userCanAccessMenu($user, $child);
                })->map(function ($child) {
                    $child->url = self::resolveUrl($child->url);
                    return $child;
                })->values(); // Reset array keys

                return $menu;
            })->values();
        });
    }

    /**
     * Helper: Generate Cache Key
     */
    private static function getMenuCacheKey(int $userId): string
    {
        return "sidebar_menu_{$userId}";
    }

    /**
     * Helper: Ubah Nama Route jadi URL Asli
     */
    private static function resolveUrl(?string $url): string
    {
        if (empty($url) || $url === '#') return '#';

        // Jika sudah URL absolut atau relative path
        if (str_starts_with($url, 'http') || str_starts_with($url, '/')) {
            return $url;
        }

        // Cek apakah ini nama route laravel
        if (Route::has($url)) {
            try {
                return route($url);
            } catch (\Exception $e) {
                return $url;
            }
        }

        return $url;
    }

    /**
     * Logic Cek Akses Menu
     */
    private static function userCanAccessMenu($user, $menu): bool
    {
        if ($menu->permission_id && $menu->permission) {
            if ($user->hasRole('super-admin')) return true;
            return $user->hasPermissionTo($menu->permission->name);
        }
        return true;
    }

    /**
     * Bersihkan Cache Menu User
     * Fix: Menggunakan forget() langsung alih-alih tags() agar kompatibel dengan semua cache driver
     */
    public static function clearMenuCache(int $userId): void
    {
        $key = self::getMenuCacheKey($userId);
        Cache::forget($key);
    }
}
