<?php

namespace Database\Seeders;

use App\Models\InventoryCategory;
use App\Models\InventoryWarehouse;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();

        // 1. Warehouses
        InventoryWarehouse::firstOrCreate(
            ['code' => 'WH-MAIN'],
            [
                'name' => 'Gudang Utama',
                'address' => 'Kantor Pusat Sekarwangi',
                'manager_id' => $admin?->id
            ]
        );

        // 2. Categories
        $categories = [
            'Elektronik',
            'Furniture',
            'ATK (Alat Tulis Kantor)',
            'Merchandise',
            'Inventaris Kegiatan',
            'Peralatan Kebersihan'
        ];

        foreach ($categories as $cat) {
            InventoryCategory::firstOrCreate(
                ['slug' => Str::slug($cat)],
                ['name' => $cat]
            );
        }
    }
}
