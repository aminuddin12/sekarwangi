<?php

namespace Database\Seeders;

use App\Enums\UserStatus;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Super Admin
        $user = User::firstOrCreate(
            ['email' => 'admin@sekarwangi.org'],
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'phone' => '081234567890',
                'password' => Hash::make('password'), // Ganti saat production!
                'status' => UserStatus::ACTIVE,
                'email_verified_at' => now(),
            ]
        );

        // 2. Assign Role
        $user->assignRole('super-admin');

        // 3. Create Detail Profile
        UserDetail::firstOrCreate(
            ['user_id' => $user->id],
            [
                'id_number' => 'SKW-ADMIN-001',
                'join_date' => now(),
                'country' => 'Indonesia',
                'bio' => 'Administrator Utama Sistem Sekarwangi Enterprise',
            ]
        );
    }
}
