<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. URL Groups (Pengelompokan URL)
        Schema::create('url_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique(); // e.g., 'sidebar-main', 'footer-left', 'api-v1-finance'
            $table->enum('section', ['public', 'authenticated', 'super_admin', 'system'])->default('public');
            $table->text('description')->nullable();

            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);

            // Audit Trail
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. API Routes Management (Endpoint Security)
        Schema::create('url_apis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('url_groups')->cascadeOnDelete();

            $table->string('name'); // Nama Route (e.g., 'api.users.index')
            $table->string('url'); // Endpoint (e.g., '/api/v1/users')
            $table->string('method')->default('GET'); // GET, POST, PUT, DELETE

            // Security: Spatie Permission Link (Nullable)
            // Menggunakan tipe data unsignedBigInteger manual karena tabel permissions milik Spatie
            $table->unsignedBigInteger('permission_id')->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_public')->default(false); // Jika true, bypass auth check

            // Rate Limiting khusus per endpoint (Opsional)
            $table->integer('rate_limit')->default(60); // Request per menit

            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            // Foreign key manual ke tabel permissions (Spatie)
            // Pastikan nama tabel permissions sesuai config/permission.php
            $table->foreign('permission_id')->references('id')->on('permissions')->nullOnDelete();
        });

        // 3. Authenticated URLs (Sidebar / Menu Dashboard)
        Schema::create('url_authenticated', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('url_groups')->cascadeOnDelete();
            $table->unsignedBigInteger('parent_id')->nullable(); // Hierarki Menu

            $table->string('name'); // Label Menu
            $table->string('url'); // Route Name atau Path
            $table->string('icon')->nullable(); // Class icon (Lucide/FontAwesome)
            $table->string('badge')->nullable(); // Label notifikasi kecil (e.g., "New")
            $table->string('badge_color')->nullable(); // Warna badge
            $table->string('hint')->nullable(); // Tooltip

            $table->integer('order')->default(0);

            // Security: Wajib punya permission
            $table->unsignedBigInteger('permission_id');

            $table->boolean('is_active')->default(true);

            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('parent_id')->references('id')->on('url_authenticated')->cascadeOnDelete();
            $table->foreign('permission_id')->references('id')->on('permissions')->cascadeOnDelete();
        });

        // 4. Public URLs (Footer, Header, Landing Page Links)
        Schema::create('url_public', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('url_groups')->cascadeOnDelete();
            $table->unsignedBigInteger('parent_id')->nullable();

            $table->string('name');
            $table->string('url'); // Absolute or Relative URL
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->string('color')->nullable(); // Hex color code
            $table->string('hint')->nullable();
            $table->string('target')->default('_self'); // _blank, _self

            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('parent_id')->references('id')->on('url_public')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('url_public');
        Schema::dropIfExists('url_authenticated');
        Schema::dropIfExists('url_apis');
        Schema::dropIfExists('url_groups');
    }
};
