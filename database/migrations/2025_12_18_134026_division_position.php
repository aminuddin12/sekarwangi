<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Divisi / Departemen
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable(); // Untuk sub-divisi
            $table->timestamps();
            $table->softDeletes();
        });

        // Jabatan / Posisi
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Ketua, Sekretaris, Staff
            $table->text('description')->nullable();
            $table->integer('level')->default(1); // Hierarchy level (1=High, 10=Low)
            $table->boolean('is_executive')->default(false); // Pengurus Harian?
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
        Schema::dropIfExists('divisions');
    }
};
