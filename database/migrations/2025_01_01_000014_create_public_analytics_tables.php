<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Marketing Links (Public URL / Shortlink Tracker)
        // Fitur seperti Bit.ly internal untuk melacak klik kampanye
        Schema::create('marketing_links', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Kampanye
            $table->string('slug')->unique(); // Short URL identifier (e.g. /go/promo-januari)
            $table->text('destination_url'); // Real URL

            // UTM Parameters Builder (Opsional)
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();

            // Stats
            $table->unsignedBigInteger('click_count')->default(0);

            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Public Trackers (Structured Tracker IDs)
        // Menyimpan ID tracker populer tanpa perlu copy-paste script manual
        Schema::create('public_trackers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'Google Analytics 4', 'Facebook Pixel'
            $table->string('provider'); // google, facebook, tiktok, hotjar
            $table->string('tracking_id'); // e.g., 'G-XXXXXXX', 'UA-XXXXX'
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Public Scripts (Custom Code Injection)
        // Untuk script custom lain yang tidak tercover tracker ID (e.g., Live Chat Widget)
        Schema::create('public_scripts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('script_code'); // <script>...</script>

            // Posisi Injeksi
            $table->enum('position', ['head', 'body_start', 'body_end'])->default('head');

            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 4. Ad Placements (Lokasi Iklan) - Tabel Pendukung Ads
        Schema::create('ad_placements', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'Homepage Banner Top'
            $table->string('key')->unique(); // e.g., 'home_top_banner' (dipanggil di frontend)
            $table->string('size')->nullable(); // e.g., '728x90'
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 5. Public Ads (Manajemen Iklan)
        Schema::create('public_ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_placement_id')->constrained('ad_placements');
            $table->string('name');

            // Tipe Iklan
            // script: AdSense/Programmatic
            // image: Banner gambar manual
            // html: Custom HTML content
            $table->enum('type', ['script', 'image', 'html'])->default('script');

            // Content
            $table->longText('content')->nullable(); // Script code atau HTML
            $table->string('image_path')->nullable(); // Jika type image
            $table->string('target_url')->nullable(); // Link tujuan jika diklik

            // Jadwal Tayang
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();

            // Stats
            $table->unsignedBigInteger('impression_count')->default(0);
            $table->unsignedBigInteger('click_count')->default(0);

            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 6. Cookie Consents (Manajemen Kategori Cookie)
        // Untuk mematuhi GDPR/UU PDP
        Schema::create('cookie_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Necessary, Analytics, Marketing
            $table->string('slug')->unique(); // necessary, analytics
            $table->text('description');
            $table->boolean('is_mandatory')->default(false); // Necessary = true
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 7. Visitor Logs (Internal Analytics Sederhana)
        // Mencatat traffic masuk secara internal
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();

            // Identitas Pengunjung
            $table->string('ip_address', 45)->nullable();
            $table->string('session_id')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Jika logged in

            // Lokasi & Device
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device_type')->nullable(); // mobile, desktop, tablet
            $table->string('browser')->nullable();
            $table->string('os')->nullable();

            // Navigasi
            $table->text('url')->nullable(); // Halaman yang dikunjungi
            $table->text('referer')->nullable(); // Datang dari mana
            $table->string('method')->nullable(); // GET
            $table->timestamps();
        });

        // 8. Link Click Logs (Detail Klik Marketing Link)
        Schema::create('marketing_link_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_link_id')->constrained('marketing_links')->cascadeOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('referer')->nullable();
            $table->timestamp('clicked_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_link_clicks');
        Schema::dropIfExists('visitor_logs');
        Schema::dropIfExists('cookie_categories');
        Schema::dropIfExists('public_ads');
        Schema::dropIfExists('ad_placements');
        Schema::dropIfExists('public_scripts');
        Schema::dropIfExists('public_trackers');
        Schema::dropIfExists('marketing_links');
    }
};
