<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Kategori Berita/Artikel (Hierarki)
        Schema::create('post_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable(); // Sub-kategori
            $table->string('thumbnail')->nullable();
            $table->string('color')->nullable(); // Warna label kategori
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Self-referencing foreign key for nested categories
            $table->foreign('parent_id')->references('id')->on('post_categories')->nullOnDelete();
        });

        // 2. Tags
        Schema::create('post_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // 3. Tabel Utama Posts (Artikel)
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            // --- Konten Utama ---
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable(); // Headline tambahan
            $table->text('excerpt')->nullable(); // Ringkasan singkat untuk list view
            $table->longText('content');

            // --- Status & Jadwal ---
            $table->enum('status', ['draft', 'pending', 'published', 'scheduled', 'archived'])->default('draft');
            $table->enum('visibility', ['public', 'private', 'password_protected'])->default('public');
            $table->string('password')->nullable(); // Jika visibility password_protected
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expired_at')->nullable();

            // --- Media ---
            $table->string('thumbnail')->nullable(); // Gambar cover list
            $table->string('banner_image')->nullable(); // Gambar besar di detail
            $table->string('banner_caption')->nullable();

            // --- Fitur & Statistik ---
            $table->boolean('is_featured')->default(false); // Headline utama
            $table->boolean('is_pinned')->default(false); // Sticky post
            $table->boolean('allow_comments')->default(true);

            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('share_count')->default(0);
            $table->unsignedBigInteger('like_count')->default(0);
            $table->integer('reading_time')->default(0); // Estimasi menit baca (auto-calc)

            // --- SEO Management ---
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('canonical_url')->nullable();
            $table->boolean('is_indexable')->default(true);

            // --- Social Media Metadata ---
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Pivot: Post <-> Category
        Schema::create('category_post', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('post_categories')->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        // 5. Pivot: Post <-> Tag
        Schema::create('post_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('post_tags')->cascadeOnDelete();
            $table->timestamps();
        });

        // 6. Post Media
        Schema::create('post_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->enum('type', ['image', 'video', 'document', 'audio', 'embed'])->default('image');
            $table->string('file_path');
            $table->string('thumbnail_path')->nullable();
            $table->string('caption')->nullable();
            $table->string('alt_text')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 7. Post Comments (Komentar & Balasan) - NEW ADDITION
        Schema::create('post_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Komentator

            $table->unsignedBigInteger('parent_id')->nullable(); // Untuk Nested Comments (Reply)
            $table->text('content');

            // Moderasi & Status
            $table->boolean('is_approved')->default(true); // Ubah false jika ingin moderasi manual
            $table->boolean('is_pinned')->default(false); // Komentar disematkan oleh admin/penulis

            // Engagement di komentar
            $table->unsignedBigInteger('like_count')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Relasi ke diri sendiri untuk reply
            $table->foreign('parent_id')->references('id')->on('post_comments')->cascadeOnDelete();
        });

        // 8. Post Revisions
        Schema::create('post_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->string('title');
            $table->longText('content');
            $table->json('changed_fields')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_revisions');
        Schema::dropIfExists('post_comments'); // Drop comments
        Schema::dropIfExists('post_media');
        Schema::dropIfExists('post_tag');
        Schema::dropIfExists('category_post');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('post_tags');
        Schema::dropIfExists('post_categories');
    }
};
