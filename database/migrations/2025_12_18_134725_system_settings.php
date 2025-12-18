<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Settings (Centralized Config)
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group'); // e.g., 'site', 'payment', 'chat', 'seo'
            $table->string('key')->unique(); // e.g., 'site_name', 'midtrans_server_key'
            $table->text('value')->nullable();
            $table->enum('type', ['text', 'boolean', 'integer', 'json', 'image'])->default('text');
            $table->string('description')->nullable();
            $table->boolean('is_system')->default(false); // Jika true, tidak bisa dihapus admin biasa
            $table->timestamps();
        });

        // 2. Custom Pages (CMS)
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content'); // HTML/Markdown
            $table->json('meta_data')->nullable(); // SEO Title, Description
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->foreignId('author_id')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
        Schema::dropIfExists('settings');
    }
};
