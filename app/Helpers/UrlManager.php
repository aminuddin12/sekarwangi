<?php

namespace App\Helpers;

use App\Models\UrlAuthenticated;
use App\Models\UrlPublic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

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
        $cacheKey = "sidebar_menu_{$user->id}_" . $user->roles->pluck('id')->implode('-');

        return Cache::remember($cacheKey, 3600, function () use ($user) {

            // 1. Ambil semua menu aktif, urutkan berdasarkan order
            $menus = UrlAuthenticated::where('is_active', true)
                ->whereNull('parent_id') // Ambil root menu dulu
                ->with(['children' => function($q) {
                    $q->where('is_active', true)->orderBy('order');
                }, 'permission'])
                ->orderBy('order')
                ->get();

            // 2. Filter berdasarkan Permission User
            return $menus->filter(function ($menu) use ($user) {
                return self::userCanAccessMenu($user, $menu);
            })->map(function ($menu) use ($user) {
                // Filter Children juga
                $menu->children = $menu->children->filter(function ($child) use ($user) {
                    return self::userCanAccessMenu($user, $child);
                });
                return $menu;
            });
        });
    }

    /**
     * Dapatkan Link Public (Footer/Header)
     */
    public static function getPublicLinks(string $groupSlug)
    {
        return Cache::remember("public_links_{$groupSlug}", 86400, function () use ($groupSlug) {
            return UrlPublic::whereHas('group', function ($q) use ($groupSlug) {
                    $q->where('slug', $groupSlug)->where('is_active', true);
                })
                ->where('is_active', true)
                ->whereNull('parent_id')
                ->with('children')
                ->orderBy('order')
                ->get();
        });
    }

    /**
     * Logic Cek Akses Menu
     */
    private static function userCanAccessMenu($user, $menu): bool
    {
        // Jika menu terhubung ke permission, cek permission user
        if ($menu->permission_id && $menu->permission) {
            // Super Admin bypass
            if ($user->hasRole('super-admin')) return true;

            return $user->hasPermissionTo($menu->permission->name);
        }

        // Jika tidak ada permission khusus, anggap boleh (atau ubah logic jadi deny all)
        return true;
    }

    /**
     * Bersihkan Cache Menu User (Panggil ini saat update role/menu)
     */
    public static function clearMenuCache(int $userId): void
    {
        // Karena key cache dinamis berdasarkan role, kita gunakan tag jika driver mendukung
        // Atau clear global user cache pattern jika pakai Redis
        // Untuk simpelnya di sini kita asumsikan logic clear spesifik
    }
}
