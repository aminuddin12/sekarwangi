<?php

namespace App\Http\Middleware;

use App\Helpers\UrlManager;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                // INI YANG KURANG: Kirim data menu sidebar ke frontend
                'sidebar_menu' => $request->user() ? UrlManager::getSidebarMenu() : [],
            ],
            // Kirim Setting Global ke Frontend (untuk logo, nama app, dll)
            'settings' => function () {
                // Bisa pakai Cache Helper SystemSetting jika mau lebih performant
                return \App\Models\Setting::where('is_public', true)
                    ->pluck('value', 'key')
                    ->toArray();
            },
        ];
    }
}
