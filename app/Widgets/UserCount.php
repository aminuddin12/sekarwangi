<?php

namespace App\Widgets;

use App\Models\User;
use App\Enums\UserStatus; // Asumsi enum, atau gunakan string biasa

class UserCount
{
    public function build(): array
    {
        $total = User::count();
        $verified = User::whereNotNull('email_verified_at')->count();
        $unverified = User::whereNull('email_verified_at')->count();
        // Asumsi ada kolom status atau pengecekan banned via soft delete/kolom status
        $banned = User::where('status', 'banned')->count();

        // Kita bisa mengembalikan satu card summary, atau array of cards.
        // Disini saya kembalikan satu card utama dengan detail di deskripsi.

        return [
            'id' => 'user_overview',
            'type' => 'StatsCard',
            'order' => 1,
            'props' => [
                'title' => 'Total Pengguna',
                'value' => $total,
                'icon' => 'users',
                'color' => 'blue',
                'description' => "Verified: {$verified} | Pending: {$unverified} | Banned: {$banned}",
            ]
        ];
    }
}
