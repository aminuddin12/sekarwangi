<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_activity_logs', function (Blueprint $table) {
            $table->id();

            // 1. Kategorisasi Log
            $table->string('log_name')->nullable()->index(); // e.g., 'auth', 'user-management', 'finance', 'system-error'
            $table->text('description'); // e.g., 'User X updated Product Y price from 10k to 15k'

            // 2. Subjek (Objek yang dimanipulasi)
            // Bisa User, Product, Schedule, dll (Polymorphic)
            $table->nullableMorphs('subject', 'subject');

            // 3. Pelaku (Causer)
            // Siapa yang melakukan aksi? (User atau System/Bot)
            $table->nullableMorphs('causer', 'causer');

            // 4. Detail Perubahan (The Core)
            // Menyimpan snapshot data 'old' dan 'attributes' (new) dalam JSON
            $table->json('properties')->nullable();

            // 5. Metadata Keamanan & Lokasi
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable(); // Browser/Device info
            $table->string('url')->nullable(); // URL tempat kejadian
            $table->string('method')->nullable(); // GET, POST, PUT, DELETE

            // 6. Level Keparahan (Severity)
            // info: aktivitas biasa (login, view)
            // warning: percobaan gagal, validasi error
            // danger: hapus data penting, ubah role admin
            // critical: system crash, exception
            $table->enum('severity', ['info', 'warning', 'danger', 'critical'])->default('info');

            // 7. Context Tambahan
            $table->string('event')->nullable(); // created, updated, deleted, restored, login, logout
            $table->uuid('batch_uuid')->nullable(); // Jika 1 aksi mentrigger banyak log, dikelompokkan disini

            $table->timestamps();

            // Indexing untuk performa pencarian log
            $table->index('created_at');
            $table->index('severity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_activity_logs');
    }
};
