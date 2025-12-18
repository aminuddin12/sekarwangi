<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Bahasa yang Didukung (Supported Languages)
        Schema::create('supported_languages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique(); // id, en, jp, ar
            $table->string('name'); // Indonesia, English
            $table->string('flag_icon')->nullable(); // path to flag icon or emoji
            $table->enum('direction', ['ltr', 'rtl'])->default('ltr'); // LTR (Left to Right) or RTL (Arabic)

            // Pengaturan Default
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);

            // Format Tanggal & Mata Uang default untuk bahasa ini
            $table->string('date_format')->default('d/m/Y');
            $table->string('currency_format')->default('IDR');

            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Tabel Terjemahan (The Core: Category, Key, Value)
        Schema::create('translations', function (Blueprint $table) {
            $table->id();

            // Relasi ke bahasa (menggunakan string code agar query lebih cepat & readable)
            $table->string('locale', 10)->index();
            $table->foreign('locale')->references('code')->on('supported_languages')->cascadeOnDelete();

            // Pengelompokan (Namespace)
            $table->string('group')->index(); // e.g., 'auth', 'validation', 'frontend', 'menu'

            // Kunci & Nilai
            $table->string('key')->index(); // e.g., 'failed', 'welcome_message'
            $table->longText('value')->nullable(); // e.g., 'Identitas tersebut tidak cocok.'

            // Metadata tambahan (Opsional)
            $table->boolean('is_json')->default(false); // Jika value berisi JSON structure

            $table->timestamps();

            // Memastikan tidak ada duplikasi key dalam satu grup & bahasa
            $table->unique(['locale', 'group', 'key']);
        });

        // 3. Seeding Data Awal (Set ID sebagai Default)
        DB::table('supported_languages')->insert([
            [
                'code' => 'id',
                'name' => 'Bahasa Indonesia',
                'flag_icon' => '🇮🇩',
                'direction' => 'ltr',
                'is_default' => true,
                'is_active' => true,
                'date_format' => 'd F Y',
                'currency_format' => 'Rp',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'en',
                'name' => 'English',
                'flag_icon' => '🇺🇸',
                'direction' => 'ltr',
                'is_default' => false,
                'is_active' => true,
                'date_format' => 'Y-m-d',
                'currency_format' => '$',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('translations');
        Schema::dropIfExists('supported_languages');
    }
};
