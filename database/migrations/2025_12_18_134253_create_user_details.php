<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Identitas Karyawan/Anggota
            $table->string('id_number')->unique()->nullable()->comment('NIP / Nomor Anggota');
            $table->string('identity_card_number')->nullable()->comment('KTP/Passport');

            // Personal Info
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->enum('marital_status', ['single', 'married', 'widowed'])->nullable();

            // Address
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('Indonesia');

            // Kepegawaian
            $table->foreignId('position_id')->nullable()->constrained('positions')->nullOnDelete();
            $table->date('join_date')->nullable();
            $table->date('resign_date')->nullable();

            // Approval (New Request)
            $table->foreignId('join_approved_by')->nullable()->constrained('users')->comment('Super Admin/Admin who approved this user');

            // Bio & Social
            $table->text('bio')->nullable();
            $table->json('social_links')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel Pivot untuk Multi-Divisi (Many-to-Many)
        Schema::create('division_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('division_id')->constrained('divisions')->cascadeOnDelete();
            $table->boolean('is_primary')->default(false); // Menandai divisi utama jika ada rangkap
            $table->timestamps();

            $table->unique(['user_id', 'division_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('division_user');
        Schema::dropIfExists('user_details');
    }
};
