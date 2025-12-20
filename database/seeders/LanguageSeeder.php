<?php

namespace Database\Seeders;

use App\Models\SupportedLanguage;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        SupportedLanguage::updateOrCreate(
            ['code' => 'id'],
            [
                'name' => 'Bahasa Indonesia',
                'flag_icon' => '🇮🇩',
                'is_default' => true,
                'is_active' => true,
                'date_format' => 'd F Y',
                'currency_format' => 'Rp',
            ]
        );

        SupportedLanguage::updateOrCreate(
            ['code' => 'en'],
            [
                'name' => 'English',
                'flag_icon' => '🇺🇸',
                'is_default' => false,
                'is_active' => true,
                'date_format' => 'Y-m-d',
                'currency_format' => '$',
            ]
        );
    }
}
