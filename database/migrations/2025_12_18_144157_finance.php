<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Kategori Keuangan / Chart of Accounts (COA)
        // Standar Akuntansi: Kode Akun, Nama, Tipe (Asset, Liability, Equity, Revenue, Expense)
        Schema::create('finance_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Contoh: 101 (Kas), 401 (Pendapatan Jasa)
            $table->string('name');
            $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Anggaran (Budgets)
        // Untuk membandingkan Realisasi vs Rencana
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_category_id')->constrained('finance_categories')->cascadeOnDelete();
            $table->string('fiscal_year', 4); // Contoh: 2025
            $table->decimal('amount', 15, 2); // Batas anggaran
            $table->date('start_date');
            $table->date('end_date');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // 3. Payroll / Gaji (Expanded Enterprise Grade)
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('slip_number')->unique(); // ID unik slip gaji (misal: SLIP/2025/01/001)
            $table->string('period'); // Format: YYYY-MM

            // Komponen Gaji Utama
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('total_allowances', 15, 2)->default(0);
            $table->decimal('total_deductions', 15, 2)->default(0);

            // Komponen Variabel
            $table->decimal('overtime_pay', 15, 2)->default(0); // Uang Lembur
            $table->decimal('bonus', 15, 2)->default(0); // Bonus Kinerja/THR
            $table->decimal('tax_amount', 15, 2)->default(0); // PPh 21
            $table->decimal('reimbursement', 15, 2)->default(0); // Penggantian biaya

            // Total Bersih
            $table->decimal('net_salary', 15, 2);

            // Detail JSON (Menyimpan rincian agar tidak perlu membuat ribuan kolom baru)
            // Contoh: [{"name": "Tunjangan Makan", "amount": 500000}, {"name": "Tunjangan Transport", "amount": 200000}]
            $table->json('allowance_details')->nullable();
            // Contoh: [{"name": "BPJS Kesehatan", "amount": 150000}, {"name": "Kasbon", "amount": 50000}]
            $table->json('deduction_details')->nullable();

            // Informasi Pembayaran
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'paid', 'cancelled'])->default('draft');
            $table->string('payment_method')->nullable(); // Bank Transfer, Cash
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->date('payment_date')->nullable();

            // Audit Trail
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Kas Organisasi / Transaksi Keuangan (Advanced Cash Book)
        Schema::create('finance_records', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique(); // INV-001, EXP-2025-002
            $table->string('title');
            $table->text('description')->nullable();

            $table->enum('transaction_type', ['income', 'expense', 'transfer']);

            $table->decimal('amount', 15, 2);
            $table->date('transaction_date');

            // Kategorisasi Akun (Penting untuk Laporan Laba Rugi/Neraca)
            $table->foreignId('finance_category_id')->nullable()->constrained('finance_categories')->nullOnDelete();

            // Relasi Polimorfik (Agar transaksi bisa ditag ke Event/Project/Order tertentu)
            // referenceable_type = 'App\Models\Schedule' (Event), referenceable_id = 1
            $table->nullableMorphs('referenceable');

            // Data Pihak Ketiga
            $table->string('payer_payee_name')->nullable(); // Nama Penyetor atau Penerima Dana
            $table->string('payment_method')->nullable(); // Bank Transfer, Cash, E-Wallet

            // Bukti & Status
            $table->string('receipt_image')->nullable();
            $table->string('attachment_file')->nullable(); // Dokumen pendukung (Invoice PDF dll)
            $table->decimal('tax_amount', 15, 2)->default(0); // PPN/PPh jika ada

            // Status Rekonsiliasi
            $table->enum('status', ['pending', 'cleared', 'void', 'reconciled'])->default('pending');

            // Audit
            $table->foreignId('recorded_by')->constrained('users');
            $table->foreignId('verified_by')->nullable()->constrained('users');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_records');
        Schema::dropIfExists('payrolls');
        Schema::dropIfExists('budgets');
        Schema::dropIfExists('finance_categories');
    }
};
