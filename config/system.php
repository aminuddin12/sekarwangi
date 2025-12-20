<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Identitas Perusahaan & Branding Internal
    |--------------------------------------------------------------------------
    |
    | Konfigurasi ini digunakan untuk kop surat, laporan PDF, dan invoice.
    | Berbeda dengan config('app.name'), ini lebih detail untuk operasional.
    |
    */
    'company' => [
        'name' => 'Sekarwangi Enterprise',
        'legal_name' => 'PT. Sekarwangi Tulungagung',
        'code' => 'SKW', // Prefix global
        'address' => env('COMPANY_ADDRESS', 'Jl. Raya Tulungagung No. 123, Jawa Timur'),
        'phone' => env('COMPANY_PHONE', '+6281234567890'),
        'email' => env('COMPANY_EMAIL', 'admin@sekarwangi.org'),
        'tax_id' => env('COMPANY_TAX_ID', '00.000.000.0-000.000'), // NPWP
        'website' => env('APP_URL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Modul Keuangan (Finance & Accounting)
    |--------------------------------------------------------------------------
    |
    | Parameter krusial untuk perhitungan uang, pajak, dan penomoran dokumen.
    |
    */
    'finance' => [
        'fiscal_year_start_month' => 1, // 1 = Januari
        'currency_code' => 'IDR',
        'currency_symbol' => 'Rp',

        // Pengaturan Pajak
        'tax' => [
            'default_rate' => 11, // PPN 11%
            'enabled' => true,
            'include_in_price' => false, // Harga produk belum termasuk pajak
        ],

        // Format Penomoran Dokumen (Sequence)
        // {Y}=Tahun 4 digit, {y}=Tahun 2 digit, {m}=Bulan, {d}=Tanggal, {ROMAN}=Bulan Romawi, {SEQ}=Urutan
        'numbering' => [
            'invoice' => 'INV/{Y}/{ROMAN}/{SEQ}', // Contoh: INV/2025/XII/0001
            'payment' => 'PAY/{y}{m}/{SEQ}',      // Contoh: PAY/2512/0001
            'payroll' => 'SLIP/{Y}/{m}/{SEQ}',    // Contoh: SLIP/2025/12/001
            'sequence_pad_length' => 5, // Panjang digit urutan (00001)
        ],

        // Ambang Batas (Threshold) untuk Audit
        'audit_threshold' => 10000000, // Transaksi di atas 10jt butuh approval tambahan/audit log merah

        // Akun-akun Default (COA Codes) untuk Automasi Jurnal
        'coa' => [
            'cash_on_hand' => '1101',
            'bank_default' => '1102',
            'accounts_receivable' => '1201', // Piutang
            'accounts_payable' => '2101',    // Utang
            'sales_revenue' => '4101',       // Pendapatan Penjualan
            'cogs' => '5101',                // HPP
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Modul Inventaris & Gudang
    |--------------------------------------------------------------------------
    */
    'inventory' => [
        // Metode Valuasi Persediaan (Penting untuk Laporan Keuangan)
        // Options: 'FIFO' (First In First Out), 'LIFO', 'AVCO' (Average Cost)
        'valuation_method' => 'FIFO',

        'barcode' => [
            'format' => 'C128', // Code 128 (Support angka & huruf)
            'width' => 2,
            'height' => 50,
            'color' => [0, 0, 0], // Hitam
        ],

        'sku' => [
            'auto_generate' => true,
            'prefix' => 'ITM',
            'separator' => '-',
        ],

        // Notifikasi Stok
        'alerts' => [
            'low_stock_threshold' => 10, // Peringatan stok menipis
            'critical_stock_threshold' => 3, // Peringatan stok kritis (merah)
            'prevent_negative_stock' => true, // Cegah transaksi jika stok 0
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Modul Commerce & Transaksi
    |--------------------------------------------------------------------------
    */
    'commerce' => [
        'order' => [
            'auto_cancel_pending_hours' => 24, // Batalkan order pending setelah 24 jam
            'invoice_due_days' => 3, // Jatuh tempo pembayaran
        ],

        'shipping' => [
            'origin_city_id' => 489, // ID Kota Tulungagung (RajaOngkir Code)
            'default_weight' => 1000, // Gram
            'max_package_weight' => 30000, // 30kg
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Modul Keanggotaan & Verifikasi
    |--------------------------------------------------------------------------
    */
    'membership' => [
        'id_card' => [
            'prefix' => 'SKW',
            'format' => '{PREFIX}-{Y}{m}-{RANDOM}', // SKW-202501-AB12
            'qr_version' => 5,
        ],

        'verification' => [
            'otp_expiry_minutes' => 5, // OTP WA kadaluarsa
            'email_link_expiry_minutes' => 60,
            'max_otp_attempts' => 3, // Blokir setelah 3x salah
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | System Maintenance & Upload Constraints
    |--------------------------------------------------------------------------
    */
    'system' => [
        // Upload Limits per Tipe Dokumen
        'uploads' => [
            'profile_photo' => ['max' => 2048, 'types' => 'jpg,jpeg,png,webp'],
            'product_image' => ['max' => 5120, 'types' => 'jpg,jpeg,png,webp'], // 5MB
            'documents'     => ['max' => 10240, 'types' => 'pdf,doc,docx,xls,xlsx'], // 10MB
            'evidence'      => ['max' => 5120, 'types' => 'jpg,jpeg,png,pdf'], // Bukti transfer/absen
        ],

        // Cache Lifetime (dalam detik)
        'cache' => [
            'settings' => 86400, // 24 jam
            'menu_structure' => 3600, // 1 jam
            'statistics' => 300, // 5 menit
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Laporan PDF & Export
    |--------------------------------------------------------------------------
    */
    'reports' => [
        'paper_size' => 'a4', // a4, letter, legal
        'orientation' => 'portrait',
        'enable_watermark' => true,
        'footer_text' => 'Dicetak otomatis oleh Sistem Informasi Sekarwangi Enterprise.',
    ],
];
