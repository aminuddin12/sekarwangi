<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // 1. Core Access (Wajib duluan)
            RolePermissionSeeder::class,
            UserSeeder::class,

            // 2. System Config
            SystemSettingSeeder::class,
            LanguageSeeder::class,

            // 3. Master Data
            FinanceSeeder::class,
            InventorySeeder::class,

            // 4. Navigation
            MenuSeeder::class,

            CmsPageSeeder::class,
        ]);
    }
}
