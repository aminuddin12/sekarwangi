<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Grup Chat / Saluran
        Schema::create('chat_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // Nullable jika private chat (1-on-1)
            $table->string('slug')->unique()->nullable(); // Untuk link invite group
            $table->enum('type', ['private', 'group', 'channel', 'community'])->default('private');
            $table->string('avatar')->nullable();
            $table->text('description')->nullable();

            // Konfigurasi Grup
            $table->boolean('is_public')->default(false); // Apakah bisa dicari?
            $table->boolean('only_admins_can_post')->default(false); // Khusus Channel

            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Anggota Grup (Pivot)
        Schema::create('chat_group_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_group_id')->constrained('chat_groups')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Role & Permissions dalam Grup
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_muted')->default(false); // Mute notifikasi

            // Kolom Baru: Alias / Nama Panggilan di Grup
            $table->string('alias')->nullable();

            // Timestamp bergabung
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();

            // Memastikan user tidak ganda dalam satu grup
            $table->unique(['chat_group_id', 'user_id']);
        });

        // 3. Pesan / Chat (Renamed from 'messages' to 'chats')
        Schema::create('chats', function (Blueprint $table) {
            $table->id();

            // A. Relasi Pengirim & Penerima
            // Jika chat grup, chat_group_id diisi. Jika personal (DM), receiver_id diisi.
            $table->foreignId('chat_group_id')->nullable()->constrained('chat_groups')->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users');
            $table->foreignId('receiver_id')->nullable()->constrained('users'); // Untuk Direct Message

            // B. Fitur Reply (Balasan)
            $table->foreignId('reply_id')->nullable()->constrained('chats')->nullOnDelete();

            // C. Konten
            $table->longText('content')->nullable(); // Support pesan panjang
            $table->string('attachment')->nullable(); // File path
            $table->string('attachment_type')->nullable(); // mime type

            // Tipe Pesan Lengkap
            $table->enum('type', [
                'text', 'image', 'video', 'audio', 'file',
                'sticker', 'location', 'contact', 'system', 'poll'
            ])->default('text');

            // D. Status Pesan
            $table->timestamp('read_at')->nullable(); // Kapan dibaca (Centang Biru)
            $table->timestamp('delivered_at')->nullable(); // Kapan sampai ke server/device

            // E. Metadata Tambahan (JSON)
            // Bisa menyimpan: durasi audio, koordinat lokasi, pilihan polling, reaksi emoji
            $table->json('metadata')->nullable();

            // F. Fitur Tarik Pesan / Edit
            $table->boolean('is_edited')->default(false);
            $table->boolean('is_forwarded')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Tabel Status Pesan (Read Receipts untuk Grup)
        // Karena di grup, 'read_at' di tabel chats saja tidak cukup (banyak user)
        Schema::create('chat_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained('chats')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('read_at')->useCurrent();

            $table->unique(['chat_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_reads');
        Schema::dropIfExists('chats');
        Schema::dropIfExists('chat_group_users');
        Schema::dropIfExists('chat_groups');
    }
};
