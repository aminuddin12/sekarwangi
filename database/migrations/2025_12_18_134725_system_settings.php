<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Settings (Centralized Config with Hierarchy)
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            // Identifikasi & Nilai
            $table->string('group')->index(); // Pengelompokan (e.g., 'site', 'email', 'social')
            $table->string('key')->unique(); // Key unik (e.g., 'site_name', 'smtp_host')
            $table->text('value')->nullable(); // Nilai konfigurasi

            // Metadata Tampilan & Tipe Data
            $table->enum('type', [
                'text', 'textarea', 'boolean', 'integer', 'float',
                'json', 'image', 'file', 'code', 'select', 'color', 'date'
            ])->default('text');
            $table->string('description')->nullable(); // Tooltip/Penjelasan helper
            $table->string('icon')->nullable(); // Icon class (e.g., 'lucide-settings')

            // Struktur Hierarki & Urutan
            $table->unsignedBigInteger('parent_id')->nullable(); // Untuk Sub-setting
            $table->integer('order')->default(0); // Urutan tampilan di dashboard

            // Keamanan & Akses
            $table->boolean('is_system')->default(false); // True = Tidak bisa dihapus, hanya edit value
            $table->boolean('is_public')->default(false); // True = Aman diekspos ke API public/frontend
            $table->boolean('is_encrypted')->default(false); // True = Value dienkripsi di DB (e.g., API Key)

            // Audit Trail
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete(); // Siapa yang terakhir ubah

            $table->timestamps();

            // Self-referencing Foreign Key
            $table->foreign('parent_id')->references('id')->on('settings')->nullOnDelete();
        });

        // 2. API Accounts (Integrasi Pihak Ketiga: Google, WA Gateway, OpenAI, dll)
        Schema::create('api_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->index(); // e.g., 'google_maps', 'whatsapp_fonnte', 'openai'
            $table->string('name'); // Nama User-friendly (e.g., 'WhatsApp Marketing')

            // Kredensial Dasar
            $table->string('api_key')->nullable();
            $table->text('api_secret')->nullable(); // Wajib dienkripsi via Model
            $table->string('app_id')->nullable();

            // Endpoint Configuration
            $table->string('endpoint_url')->nullable(); // Base URL API
            $table->string('webhook_secret')->nullable(); // Untuk verifikasi webhook masuk

            // Token Management (OAuth2)
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestamp('expires_at')->nullable();

            // Konfigurasi Tambahan (JSON)
            $table->json('additional_config')->nullable(); // Custom config spesifik provider

            // Status
            $table->boolean('is_active')->default(true);
            $table->string('environment')->default('production'); // production, sandbox/staging

            $table->timestamps();
            $table->softDeletes();
        });

        // 3. API Payments (Konfigurasi Payment Gateway: Midtrans, Xendit, Stripe)
        Schema::create('api_payments', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->unique(); // e.g., 'midtrans', 'xendit'
            $table->string('name'); // e.g., 'Midtrans Payment'
            $table->string('logo')->nullable();

            // Kredensial
            $table->string('merchant_id')->nullable();
            $table->string('client_key')->nullable(); // Public Key
            $table->string('server_key')->nullable(); // Secret Key (Encrypted)

            // Mode Operasi
            $table->enum('mode', ['sandbox', 'production'])->default('sandbox');

            // Pengaturan Biaya Layanan (Platform Fee)
            $table->decimal('fee_flat', 15, 2)->default(0); // Biaya tetap per transaksi
            $table->decimal('fee_percent', 5, 2)->default(0); // Biaya persentase

            $table->boolean('is_active')->default(false);

            $table->timestamps();
        });

        // 4. System Maintenance Schedules (Jadwal Maintenance)
        Schema::create('maintenance_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message'); // Pesan yang muncul ke user
            $table->timestamp('start_at');
            $table->timestamp('end_at')->nullable();

            $table->boolean('is_active')->default(true);
            $table->enum('mode', ['full_down', 'read_only'])->default('full_down'); // Full mati atau cuma gak bisa tulis

            // Whitelist IP (Optional, untuk admin tetap bisa akses saat maintenance)
            $table->json('allowed_ips')->nullable();

            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_schedules');
        Schema::dropIfExists('api_payments');
        Schema::dropIfExists('api_accounts');
        Schema::dropIfExists('settings');
    }
};
