<?php

namespace App\Helpers;

use App\Models\UrlAuthenticated;
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

        // Cache Key unik per User ID
        $cacheKey = self::getMenuCacheKey($user->id);

        return Cache::remember($cacheKey, 3600, function () use ($user) {

            // 1. Ambil semua menu aktif (Parent Only)
            // Eager load 'group' untuk kebutuhan grouping di frontend
            $menus = UrlAuthenticated::where('is_active', true)
                ->whereNull('parent_id')
                ->with(['group', 'children' => function($q) {
                    $q->where('is_active', true)->orderBy('order');
                }, 'permission'])
                ->get()
                // Sort manual collection: Group Order -> Menu Order
                ->sortBy([
                    ['group.order', 'asc'],
                    ['order', 'asc'],
                ]);

            // 2. Filter & Transformasi Data
            return $menus->filter(function ($menu) use ($user) {
                return self::userCanAccessMenu($user, $menu);
            })->map(function ($menu) use ($user) {

                // Resolve URL Parent
                $menuUrl = self::resolveUrl($menu->url, $menu->route, $user);

                // Filter & Resolve Children
                $children = $menu->children->filter(function ($child) use ($user) {
                    return self::userCanAccessMenu($user, $child);
                })->map(function ($child) use ($user) {
                    return [
                        'id' => $child->id,
                        'name' => $child->name,
                        'url' => self::resolveUrl($child->url, $child->route, $user),
                        'icon' => $child->icon,
                        'parent_id' => $child->parent_id,
                    ];
                })->values()->toArray();

                return [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'url' => $menuUrl,
                    'icon' => $menu->icon,
                    'children' => $children,
                    // DATA PENTING: Group Info untuk Frontend
                    'group' => $menu->group ? [
                        'name' => $menu->group->name,
                        'order' => $menu->group->order,
                        'slug' => $menu->group->slug,
                    ] : null,
                ];
            })->values()->toArray();
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
     * Helper: Ubah Nama Route jadi URL Asli dengan Parameter Dinamis
     */
    private static function resolveUrl(?string $url, ?string $routeName, $user): string
    {
        // Prioritas 1: Gunakan Route Name (Lebih aman jika URL berubah)
        if (!empty($routeName) && Route::has($routeName)) {
            try {
                $params = [];

                // Handle route admin yang butuh parameter {panel_role}
                // Cek jika route mengandung prefix admin kita (misal 'super.')
                if (str_contains($routeName, 'super.')) {
                    // Logika Prefix Dinamis:
                    // 1. Jika Super Admin -> 'super-admin'
                    // 2. Jika bukan -> Ambil nama role pertama user (misal: 'admin', 'member')
                    if ($user->hasRole('super-admin')) {
                         $roleParam = 'super-admin';
                    } else {
                        $roleParam = $user->roles->first()?->name ?? 'member';
                    }

                    $params['panel_role'] = $roleParam;
                }

                return route($routeName, $params);
            } catch (\Exception $e) {
                // Jika gagal generate (misal parameter kurang), fallback ke URL path atau #
                return '#';
            }
        }

        // Prioritas 2: Gunakan URL Path manual dari database
        if (!empty($url)) {
            if ($url === '#') return '#';
            if (str_starts_with($url, 'http') || str_starts_with($url, '/')) {
                return $url;
            }
            return '/' . ltrim($url, '/'); // Pastikan ada slash depan
        }

        return '#';
    }

    /**
     * Logic Cek Akses Menu
     */
    private static function userCanAccessMenu($user, $menu): bool
    {
        // Super Admin bypass semua permission -> TAMPILKAN SEMUA
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Cek permission jika menu ini dilindungi
        if ($menu->permission_id) {
            // Pastikan relasi permission terload dan ada datanya
            if ($menu->relationLoaded('permission') && $menu->permission) {
                return $user->hasPermissionTo($menu->permission->name);
            }
            // Jika ID ada tapi data null (broken link), tolak akses demi keamanan
            return false;
        }

        // Jika tidak ada permission khusus, izinkan (Public Menu dalam Auth area)
        return true;
    }

    /**
     * Bersihkan Cache Menu User
     */
    public static function clearMenuCache(int $userId): void
    {
        $key = self::getMenuCacheKey($userId);
        Cache::forget($key);
    }
}
