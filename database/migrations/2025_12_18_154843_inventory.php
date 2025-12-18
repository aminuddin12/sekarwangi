<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Kategori Inventaris
        Schema::create('inventory_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('inventory_categories')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Lokasi Gudang / Penyimpanan
        Schema::create('inventory_warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: "Gudang Utama", "Lemari A Sekretariat"
            $table->string('code')->unique();
            $table->text('address')->nullable();
            $table->foreignId('manager_id')->nullable()->constrained('users'); // Penanggung jawab
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Tabel Utama Barang (Items)
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();

            // Klasifikasi
            $table->foreignId('category_id')->constrained('inventory_categories');
            $table->foreignId('warehouse_id')->constrained('inventory_warehouses');
            $table->foreignId('supplier_id')->nullable()->constrained('users'); // Vendor supplier (opsional)

            // Identitas Barang
            $table->string('name');
            $table->string('sku')->unique(); // Stock Keeping Unit (Kode Unik)
            $table->string('barcode')->nullable()->index(); // Scan Barcode
            $table->string('brand')->nullable(); // Merk
            $table->string('model')->nullable(); // Tipe/Model
            $table->longText('description')->nullable();

            // Data Stok & Fisik
            $table->integer('quantity')->default(0); // Stok Aktif
            $table->integer('minimum_stock')->default(5); // Alert jika stok di bawah ini
            $table->string('unit')->default('pcs'); // pcs, box, kg, set

            // Nilai Aset
            $table->decimal('purchase_price', 15, 2)->default(0); // Harga Beli
            $table->decimal('selling_price', 15, 2)->nullable(); // Harga Jual (jika dijual kembali)
            $table->date('purchase_date')->nullable();

            // Kondisi & Status
            $table->enum('condition', ['good', 'damaged', 'repair', 'lost'])->default('good');
            $table->enum('status', ['available', 'out_of_stock', 'discontinued', 'archived'])->default('available');
            $table->boolean('is_lendable')->default(true); // Bisa dipinjam?
            $table->boolean('is_saleable')->default(false); // Bisa dijual?

            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Gambar Barang
        Schema::create('inventory_item_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->cascadeOnDelete();
            $table->string('image_path');
            $table->boolean('is_primary')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 5. Transaksi Inventaris (Masuk, Keluar, Jual, Musnah)
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_item_id')->constrained('inventory_items');
            $table->foreignId('user_id')->constrained('users'); // Petugas yang input

            // Jenis Transaksi
            // in: Barang Masuk (Pembelian/Hibah)
            // out: Barang Keluar (Pemakaian Habis Pakai)
            // sale: Penjualan
            // adjustment: Stock Opname (Koreksi stok)
            // write_off: Penghapusan (Rusak/Hilang)
            $table->enum('type', ['in', 'out', 'sale', 'adjustment', 'write_off']);

            $table->integer('quantity'); // Positif atau Negatif
            $table->integer('stock_before'); // Snapshot stok sebelum transaksi
            $table->integer('stock_after'); // Snapshot stok setelah transaksi

            $table->string('reference_number')->nullable(); // No Invoice / PO
            $table->text('notes')->nullable();
            $table->date('transaction_date');

            $table->timestamps();
            $table->softDeletes();
        });

        // 6. Peminjaman Barang (Loans)
        Schema::create('inventory_loans', function (Blueprint $table) {
            $table->id();
            $table->string('loan_code')->unique(); // LOAN-2025-001
            $table->foreignId('inventory_item_id')->constrained('inventory_items');
            $table->foreignId('borrower_id')->constrained('users'); // Peminjam (Anggota)

            $table->integer('quantity');
            $table->date('loan_date');
            $table->date('due_date'); // Tanggal Wajib Kembali
            $table->date('return_date')->nullable(); // Tanggal Realisasi Kembali

            // Status Peminjaman
            $table->enum('status', ['pending', 'approved', 'active', 'returned', 'overdue', 'lost'])->default('pending');

            $table->enum('return_condition', ['good', 'damaged', 'repair'])->nullable(); // Kondisi saat kembali
            $table->decimal('fine_amount', 15, 2)->default(0); // Denda jika ada

            $table->text('purpose')->nullable(); // Keperluan pinjam
            $table->foreignId('approved_by')->nullable()->constrained('users'); // Petugas yang menyetujui
            $table->text('admin_notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // 7. Log Aktivitas Inventaris (Audit Trail Lengkap)
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_item_id')->nullable()->constrained('inventory_items')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Pelaku

            $table->string('action'); // created, updated, deleted, status_changed, stock_changed
            $table->text('description'); // Detail deskriptif: "Mengubah stok dari 10 menjadi 15"
            $table->json('changes')->nullable(); // Menyimpan data old_values dan new_values

            // Tracking Keamanan
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
        Schema::dropIfExists('inventory_loans');
        Schema::dropIfExists('inventory_transactions');
        Schema::dropIfExists('inventory_item_images');
        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('inventory_warehouses');
        Schema::dropIfExists('inventory_categories');
    }
};
