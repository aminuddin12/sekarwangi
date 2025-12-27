<?php

namespace App\Helpers;

use App\Models\UrlPublic;

class MenuHelper
{
    public static function getPublicMenu($target)
    {
        return UrlPublic::where('target', $target)
            ->where('is_active', true)
            ->whereNull('parent_id') // Ambil menu utama saja dulu
            ->with(['children' => function ($query) {
                $query->where('is_active', true)->orderBy('order'); // Ambil submenu
            }])
            ->orderBy('order')
            ->get();
    }
}
