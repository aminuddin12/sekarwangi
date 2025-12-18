<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Page Types (Tipe Halaman)
        // Membedakan antara Landing Page, Article, Portfolio, dll.
        Schema::create('page_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'Landing Page', 'Blog Post', 'Event Page'
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Page Templates / Styles (Template Desain)
        // Menyimpan konfigurasi layout atau referensi file view blade/react component
        Schema::create('page_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'Full Width Hero', 'Sidebar Right', 'Minimalist'
            $table->string('view_path'); // Path ke komponen/view file: 'Pages/Templates/FullWidth'
            $table->json('default_config')->nullable(); // Default JSON config untuk template ini
            $table->string('thumbnail')->nullable(); // Preview gambar template
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Main Pages Table (Tabel Utama)
        Schema::create('pages', function (Blueprint $table) {
            $table->id();

            // Identitas Halaman
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();

            // Relasi Tipe & Template
            $table->foreignId('page_type_id')->nullable()->constrained('page_types')->nullOnDelete();
            $table->foreignId('page_template_id')->nullable()->constrained('page_templates')->nullOnDelete();

            // Konten Utama
            // Bisa HTML mentah atau JSON Structure untuk Page Builder (e.g., GrapeJS / Gutenberg like)
            $table->longText('content')->nullable();
            $table->json('content_structure')->nullable(); // Struktur blok konten jika pakai page builder

            // Status & Visibility
            $table->enum('status', ['draft', 'published', 'scheduled', 'archived', 'private'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->string('password')->nullable(); // Jika status private/password protected

            // SEO & Metadata (Advanced)
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('og_image')->nullable(); // Open Graph Image
            $table->boolean('is_indexable')->default(true); // Robots noindex

            // Tampilan & Style Khusus Per Halaman
            $table->string('featured_image')->nullable();
            $table->json('custom_css')->nullable(); // CSS khusus halaman ini
            $table->json('custom_js')->nullable(); // JS khusus halaman ini

            // Audit Trail
            $table->foreignId('author_id')->constrained('users'); // Pembuat
            $table->foreignId('last_editor_id')->nullable()->constrained('users'); // Editor terakhir

            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Page Sections / Components (Komponen Reusable)
        // Jika halaman dibangun dari potongan-potongan section (Hero, Testimonial, FAQ)
        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
            $table->string('section_name'); // e.g., 'Hero Section'
            $table->string('component_type'); // e.g., 'hero-slider', 'faq-accordion'
            $table->integer('order')->default(0);
            $table->json('data')->nullable(); // Data konten section ini (Title, Image, Text)
            $table->json('style_config')->nullable(); // Background color, padding, margin
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        // 5. Page Revisions (History Perubahan)
        // Penting untuk rollback jika ada kesalahan edit
        Schema::create('page_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users'); // Siapa yang merevisi

            // Snapshot data sebelum berubah
            $table->string('title');
            $table->longText('content')->nullable();
            $table->json('content_structure')->nullable();

            $table->text('revision_note')->nullable(); // Catatan: "Mengubah typo di paragraf 1"
            $table->timestamps();
        });

        // 6. Page Analytics (Sederhana)
        // Menyimpan total view per hari/bulan (Agregat) agar tidak memberatkan query log
        Schema::create('page_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
            $table->date('date');
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('unique_visitors')->default(0);
            $table->decimal('avg_time_spent', 10, 2)->default(0); // Detik
            $table->timestamps();

            $table->unique(['page_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_analytics');
        Schema::dropIfExists('page_revisions');
        Schema::dropIfExists('page_sections');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('page_templates');
        Schema::dropIfExists('page_types');
    }
};
