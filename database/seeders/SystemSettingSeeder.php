<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first(); // Ambil user pertama sebagai updater (biasanya Super Admin)
        $userId = $admin?->id;

        // Ambil config dari file config/system.php (jika ada), atau gunakan default
        $conf = config('system');

        // --- 1. IDENTITAS PERUSAHAAN (SITE) ---
        $this->createSetting('site', 'site_name', $conf['company']['name'] ?? 'Sekarwangi Enterprise', 'text', 'Nama Aplikasi/Perusahaan', $userId);
        $this->createSetting('site', 'company_legal_name', $conf['company']['legal_name'] ?? 'PT. Sekarwangi Tulungagung Sejahtera', 'text', 'Nama Legal Perusahaan (untuk Faktur)', $userId);
        $this->createSetting('site', 'company_code', $conf['company']['code'] ?? 'SKW', 'text', 'Kode Identitas Perusahaan', $userId);
        $this->createSetting('site', 'company_address', $conf['company']['address'] ?? 'Jl. Raya Tulungagung No. 123', 'textarea', 'Alamat Kantor Pusat', $userId);
        $this->createSetting('site', 'company_phone', $conf['company']['phone'] ?? '+6281234567890', 'text', 'Nomor Telepon Resmi', $userId);
        $this->createSetting('site', 'company_email', $conf['company']['email'] ?? 'admin@sekarwangi.org', 'text', 'Email Resmi Korespondensi', $userId);
        $this->createSetting('site', 'company_tax_id', $conf['company']['tax_id'] ?? '00.000.000.0-000.000', 'text', 'NPWP Perusahaan', $userId);
        $this->createSetting('site', 'company_website', $conf['company']['website'] ?? 'https://sekarwangi.org', 'text', 'Website Utama', $userId);

        // --- 2. KEUANGAN (FINANCE) ---
        $this->createSetting('finance', 'fiscal_year_start', $conf['finance']['fiscal_year_start_month'] ?? 1, 'integer', 'Bulan Awal Tahun Fiskal (1=Januari)', $userId);
        $this->createSetting('finance', 'currency_code', $conf['finance']['currency_code'] ?? 'IDR', 'text', 'Kode Mata Uang Utama', $userId);
        $this->createSetting('finance', 'currency_symbol', $conf['finance']['currency_symbol'] ?? 'Rp', 'text', 'Simbol Mata Uang', $userId);
        $this->createSetting('finance', 'tax_default_rate', $conf['finance']['tax']['default_rate'] ?? 11, 'float', 'Persentase PPN Default (%)', $userId);
        $this->createSetting('finance', 'tax_enabled', $conf['finance']['tax']['enabled'] ?? true, 'boolean', 'Aktifkan Perhitungan Pajak', $userId);
        $this->createSetting('finance', 'audit_threshold', $conf['finance']['audit_threshold'] ?? 10000000, 'integer', 'Batas Nominal Wajib Audit', $userId);

        // Format Penomoran Dokumen
        $this->createSetting('finance', 'format_invoice', $conf['finance']['numbering']['invoice'] ?? 'INV/{Y}/{ROMAN}/{SEQ}', 'text', 'Format Nomor Invoice', $userId);
        $this->createSetting('finance', 'format_payment', $conf['finance']['numbering']['payment'] ?? 'PAY/{y}{m}/{SEQ}', 'text', 'Format Nomor Pembayaran', $userId);
        $this->createSetting('finance', 'format_payroll', $conf['finance']['numbering']['payroll'] ?? 'SLIP/{Y}/{m}/{SEQ}', 'text', 'Format Slip Gaji', $userId);

        // Default Chart of Accounts (COA)
        $this->createSetting('finance', 'coa_cash_default', $conf['finance']['coa']['cash_on_hand'] ?? '1101', 'text', 'Kode Akun Kas Default', $userId);
        $this->createSetting('finance', 'coa_sales_revenue', $conf['finance']['coa']['sales_revenue'] ?? '4101', 'text', 'Kode Akun Pendapatan Penjualan', $userId);

        // --- 3. INVENTARIS (INVENTORY) ---
        $this->createSetting('inventory', 'valuation_method', $conf['inventory']['valuation_method'] ?? 'FIFO', 'select', 'Metode Valuasi Stok (FIFO/LIFO/AVCO)', $userId);
        $this->createSetting('inventory', 'low_stock_alert', $conf['inventory']['alerts']['low_stock_threshold'] ?? 10, 'integer', 'Batas Peringatan Stok Menipis', $userId);
        $this->createSetting('inventory', 'critical_stock_alert', $conf['inventory']['alerts']['critical_stock_threshold'] ?? 3, 'integer', 'Batas Peringatan Stok Kritis', $userId);
        $this->createSetting('inventory', 'prevent_negative_stock', $conf['inventory']['alerts']['prevent_negative_stock'] ?? true, 'boolean', 'Cegah Transaksi jika Stok Kosong', $userId);
        $this->createSetting('inventory', 'sku_prefix', $conf['inventory']['sku']['prefix'] ?? 'ITM', 'text', 'Prefix Default SKU Barang', $userId);
        $this->createSetting('inventory', 'barcode_format', $conf['inventory']['barcode']['format'] ?? 'C128', 'text', 'Format Barcode Standar', $userId);

        // --- 4. COMMERCE (E-COMMERCE & POS) ---
        $this->createSetting('commerce', 'order_auto_cancel_hours', $conf['commerce']['order']['auto_cancel_pending_hours'] ?? 24, 'integer', 'Batalkan Order Pending (Jam)', $userId);
        $this->createSetting('commerce', 'invoice_due_days', $conf['commerce']['order']['invoice_due_days'] ?? 3, 'integer', 'Jatuh Tempo Invoice (Hari)', $userId);
        $this->createSetting('commerce', 'shipping_origin_city', $conf['commerce']['shipping']['origin_city_id'] ?? 489, 'integer', 'ID Kota Pengiriman (RajaOngkir)', $userId);
        $this->createSetting('commerce', 'default_weight', $conf['commerce']['shipping']['default_weight'] ?? 1000, 'integer', 'Berat Default Produk (Gram)', $userId);

        // --- 5. KEANGGOTAAN (MEMBERSHIP) ---
        $this->createSetting('membership', 'id_card_format', $conf['membership']['id_card']['format'] ?? 'SKW-{Y}{m}-{RANDOM}', 'text', 'Format ID Anggota', $userId);
        $this->createSetting('membership', 'otp_expiry', $conf['membership']['verification']['otp_expiry_minutes'] ?? 5, 'integer', 'Masa Berlaku OTP (Menit)', $userId);
        $this->createSetting('membership', 'max_otp_attempts', $conf['membership']['verification']['max_otp_attempts'] ?? 3, 'integer', 'Maksimal Percobaan OTP', $userId);

        // --- 6. SISTEM (SYSTEM) ---
        $this->createSetting('system', 'upload_max_size', $conf['system']['uploads']['documents']['max'] ?? 10240, 'integer', 'Batas Upload Dokumen (KB)', $userId);
        $this->createSetting('system', 'cache_lifetime', $conf['system']['cache']['settings'] ?? 86400, 'integer', 'Durasi Cache Pengaturan (Detik)', $userId);
        $this->createSetting('system', 'maintenance_mode', false, 'boolean', 'Status Mode Maintenance', $userId);

        // --- 7. LAPORAN (REPORTS) ---
        $this->createSetting('report', 'paper_size', $conf['reports']['paper_size'] ?? 'a4', 'text', 'Ukuran Kertas Default PDF', $userId);
        $this->createSetting('report', 'watermark_enabled', $conf['reports']['enable_watermark'] ?? true, 'boolean', 'Tampilkan Watermark pada Laporan', $userId);
        $this->createSetting('report', 'footer_text', $conf['reports']['footer_text'] ?? 'Dicetak otomatis oleh Sistem Informasi Sekarwangi.', 'text', 'Teks Footer Laporan', $userId);
    }

    /**
     * Helper untuk membuat atau memperbarui setting
     */
    private function createSetting($group, $key, $value, $type, $desc, $updaterId)
    {
        Setting::updateOrCreate(
            ['key' => $key],
            [
                'group' => $group,
                'value' => (string) $value, // Cast ke string agar aman di DB text column
                'type' => $type,
                'description' => $desc,
                'is_system' => true, // Tandai sebagai setting bawaan sistem
                'is_public' => in_array($group, ['site', 'commerce']), // Beberapa grup aman untuk publik
                'is_encrypted' => false,
                'updated_by' => $updaterId
            ]
        );
    }
}
