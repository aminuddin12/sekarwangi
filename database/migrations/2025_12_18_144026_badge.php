<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable(); // FontAwesome class or Image URL
            $table->string('color')->default('#000000'); // Hex color for UI
            $table->text('description')->nullable();

            // Mengkategorikan badge (misal: 'achievement' untuk user, 'label' untuk produk)
            $table->enum('type', ['user_achievement', 'schedule_label', 'product_label', 'general'])->default('general');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('model_has_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('badge_id')->constrained('badges')->cascadeOnDelete();

            // Polymorphic relation (Bisa User, Product, Schedule, dll)
            $table->morphs('model');

            $table->timestamp('assigned_at')->useCurrent();
            $table->foreignId('assigned_by')->nullable()->constrained('users');

            $table->unique(['badge_id', 'model_id', 'model_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('model_has_badges');
        Schema::dropIfExists('badges');
    }
};
