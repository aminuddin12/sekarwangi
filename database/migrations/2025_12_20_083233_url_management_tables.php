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
            $table->string('slug')->unique();
            $table->enum('section', ['public', 'authenticated', 'super_admin', 'system'])->default('public');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. API Routes Management (Endpoint Security)
        Schema::create('url_apis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('url_groups')->cascadeOnDelete();
            $table->string('name');
            $table->string('url');
            $table->string('route')->nullable();
            $table->string('method')->default('GET');
            $table->unsignedBigInteger('permission_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_public')->default(false);
            $table->integer('rate_limit')->default(60);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('permission_id')->references('id')->on('permissions')->nullOnDelete();
        });

        // 3. Authenticated URLs (Sidebar / Menu Dashboard)
        Schema::create('url_authenticated', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('url_groups')->cascadeOnDelete();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->string('url');
            $table->string('route')->nullable();
            $table->string('icon')->nullable();
            $table->string('badge')->nullable();
            $table->string('badge_color')->nullable();
            $table->string('hint')->nullable();
            $table->integer('order')->default(0);
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
            $table->string('url');
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->string('color')->nullable();
            $table->string('hint')->nullable();
            $table->string('target')->default('_self');
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
