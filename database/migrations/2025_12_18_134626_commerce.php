<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Kategori Layanan (Service Categories)
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        // 2. Layanan Vendor (Services)
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('users');
            $table->foreignId('service_category_id')->constrained('service_categories');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('price_start_from', 15, 2);
            $table->json('features')->nullable();
            $table->string('thumbnail')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Kategori Produk (Product Categories)
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->timestamps();
        });

        // 4. Kurir Pengiriman (Couriers) - NEW & COMPLETE
        Schema::create('couriers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: JNE, J&T, SiCepat
            $table->string('code')->unique(); // Kode kurir untuk integrasi API (jne, jnt)
            $table->string('logo')->nullable(); // Logo kurir
            $table->string('website')->nullable();
            $table->text('description')->nullable();
            $table->json('service_types')->nullable(); // List layanan: ["REG", "YES", "ECO"]
            $table->string('tracking_url_template')->nullable(); // URL untuk cek resi otomatis
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 5. Produk Merchandise (Products) - UPDATED
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();

            // Perubahan: description menjadi longText
            $table->longText('description');

            // Penambahan: Spesifikasi lengkap produk (longText)
            $table->longText('specification')->nullable();

            $table->decimal('price_idr', 15, 2);
            $table->decimal('price_usd', 15, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->boolean('is_digital')->default(false);

            // Penambahan: Berat produk (gram) untuk ongkir
            $table->integer('weight_grams')->default(0);

            $table->string('thumbnail')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 6. Varian Produk (Product Variants) - NEW
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('name'); // Contoh: "Merah - XL"
            $table->string('sku')->unique()->nullable(); // Stock Keeping Unit

            // Harga override (jika beda dari produk induk)
            $table->decimal('price_idr', 15, 2)->nullable();
            $table->decimal('price_usd', 15, 2)->nullable();

            $table->integer('stock')->default(0);
            $table->integer('weight_grams')->nullable(); // Berat varian bisa berbeda
            $table->string('thumbnail')->nullable(); // Foto spesifik varian

            // Menyimpan atribut varian, misal: {"color": "Red", "size": "XL"}
            $table->json('attributes')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 7. Pivot: Produk <-> Kategori
        Schema::create('product_product_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('product_category_id')->constrained('product_categories')->cascadeOnDelete();
            $table->timestamps();
        });

        // 8. External Links
        Schema::create('product_external_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('platform_name');
            $table->string('url');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 9. Product Media
        Schema::create('product_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->enum('type', ['image', 'video', 'youtube_url']);
            $table->string('file_path');
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 10. Product Reviews
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->json('media')->nullable();
            $table->text('seller_reply')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->boolean('is_hidden')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        // 11. Transaksi / Order - UPDATED WITH SHIPPING INFO
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('user_id')->constrained('users');

            $table->decimal('total_amount', 15, 2);
            $table->string('currency', 3)->default('IDR');

            $table->enum('status', ['pending', 'paid', 'processing', 'shipped', 'completed', 'cancelled', 'refunded'])->default('pending');
            $table->string('payment_gateway')->nullable();
            $table->string('payment_token')->nullable();

            // Informasi Pengiriman (Shipping)
            $table->foreignId('courier_id')->nullable()->constrained('couriers'); // Kurir yang dipilih
            $table->string('shipping_service')->nullable(); // Layanan kurir (e.g. REG, YES)
            $table->string('shipping_tracking_number')->nullable(); // Nomor Resi
            $table->decimal('shipping_cost', 15, 2)->default(0); // Biaya Ongkir
            $table->text('shipping_address')->nullable(); // Alamat tujuan (snapshot saat order)

            $table->timestamps();
            $table->softDeletes();
        });

        // 12. Detail Order - UPDATED WITH VARIANT
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();

            // Polymorphic (Produk atau Service)
            $table->morphs('item');

            // Jika item adalah produk, referensi ke varian (Nullable jika produk tidak punya varian)
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants');

            $table->integer('quantity');
            $table->decimal('price', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->json('metadata')->nullable(); // Menyimpan detail varian/request user saat order
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('product_reviews');
        Schema::dropIfExists('product_media');
        Schema::dropIfExists('product_external_links');
        Schema::dropIfExists('product_product_category');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
        Schema::dropIfExists('couriers');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('services');
        Schema::dropIfExists('service_categories');
    }
};
