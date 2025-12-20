<?php

namespace Database\Seeders;

use App\Models\UrlAuthenticated;
use App\Models\UrlGroup;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Group Sidebar Utama
        $sidebarGroup = UrlGroup::firstOrCreate(
            ['slug' => 'sidebar-main'],
            ['name' => 'Sidebar Utama', 'section' => 'authenticated', 'is_active' => true]
        );

        // Helper untuk get Permission ID dengan fallback aman
        $getPermId = function ($name) {
            return Permission::where('name', $name)->first()->id ?? Permission::first()->id;
        };

        // Helper untuk create menu item agar kode lebih bersih
        $createMenu = function ($name, $url, $icon, $perm, $order, $parent = null) use ($sidebarGroup, $getPermId) {
            return UrlAuthenticated::firstOrCreate(
                ['url' => $url, 'group_id' => $sidebarGroup->id],
                [
                    'name' => $name,
                    'icon' => $icon,
                    'order' => $order,
                    'parent_id' => $parent?->id,
                    'permission_id' => $getPermId($perm),
                    'is_active' => true,
                ]
            );
        };

        // --- DASHBOARD (Root) ---
        $createMenu('Dashboard', 'dashboard', 'lucide:layout-dashboard', 'view-users', 1);

        // --- 1. CORE & HR (User Management) ---
        $hrParent = $createMenu('SDM & Pengguna', '#hr', 'lucide:users', 'view-users', 10);

        $createMenu('Daftar Pengguna', 'users.index', 'lucide:user', 'view-users', 1, $hrParent);
        $createMenu('Role & Hak Akses', 'roles.index', 'lucide:shield-check', 'view-roles', 2, $hrParent);
        $createMenu('Divisi & Jabatan', 'divisions.index', 'lucide:network', 'view-divisions', 3, $hrParent);
        $createMenu('Log Aktivitas', 'activity-logs.index', 'lucide:activity', 'view-activity-logs', 4, $hrParent);

        // --- 2. FINANCE (Keuangan) ---
        $finParent = $createMenu('Keuangan', '#finance', 'lucide:banknote', 'view-finance-dashboard', 20);

        $createMenu('Dashboard Keuangan', 'finance.dashboard', 'lucide:pie-chart', 'view-finance-dashboard', 1, $finParent);
        $createMenu('Catatan Transaksi', 'finance.records.index', 'lucide:receipt', 'view-finance-records', 2, $finParent);
        $createMenu('Penggajian (Payroll)', 'finance.payroll.index', 'lucide:wallet', 'view-payrolls', 3, $finParent);
        $createMenu('Anggaran (Budget)', 'finance.budgets.index', 'lucide:piggy-bank', 'view-budgets', 4, $finParent);
        $createMenu('Laporan Keuangan', 'finance.reports.index', 'lucide:file-bar-chart', 'view-finance-reports', 5, $finParent);
        $createMenu('Chart of Accounts', 'finance.categories.index', 'lucide:list-tree', 'view-finance-categories', 6, $finParent);

        // --- 3. INVENTORY (Gudang) ---
        $invParent = $createMenu('Inventaris', '#inventory', 'lucide:package', 'view-inventory-dashboard', 30);

        $createMenu('Dashboard Gudang', 'inventory.dashboard', 'lucide:layout-grid', 'view-inventory-dashboard', 1, $invParent);
        $createMenu('Stok Barang', 'inventory.items.index', 'lucide:boxes', 'view-inventory-items', 2, $invParent);
        $createMenu('Barang Masuk', 'inventory.in.index', 'lucide:arrow-down-to-line', 'create-inventory-transaction-in', 3, $invParent);
        $createMenu('Barang Keluar', 'inventory.out.index', 'lucide:arrow-up-from-line', 'create-inventory-transaction-out', 4, $invParent);
        $createMenu('Peminjaman Aset', 'inventory.loans.index', 'lucide:hand-platter', 'view-inventory-loans', 5, $invParent);
        $createMenu('Lokasi Gudang', 'inventory.warehouses.index', 'lucide:warehouse', 'view-warehouses', 6, $invParent);
        $createMenu('Opname Stok', 'inventory.adjustments.index', 'lucide:clipboard-check', 'adjust-inventory-stock', 7, $invParent);

        // --- 4. COMMERCE (Toko & Layanan) ---
        $comParent = $createMenu('Niaga & Layanan', '#commerce', 'lucide:shopping-bag', 'view-commerce-dashboard', 40);

        $createMenu('Dashboard Penjualan', 'commerce.dashboard', 'lucide:bar-chart-4', 'view-commerce-dashboard', 1, $comParent);
        $createMenu('Pesanan (Orders)', 'commerce.orders.index', 'lucide:shopping-cart', 'view-orders', 2, $comParent);
        $createMenu('Produk', 'commerce.products.index', 'lucide:tag', 'view-products', 3, $comParent);
        $createMenu('Layanan Jasa', 'commerce.services.index', 'lucide:briefcase', 'view-services', 4, $comParent);
        $createMenu('Ulasan Pelanggan', 'commerce.reviews.index', 'lucide:star', 'view-reviews', 5, $comParent);
        $createMenu('Kupon & Diskon', 'commerce.coupons.index', 'lucide:ticket', 'manage-discounts', 6, $comParent);

        // --- 5. CMS (Konten Website) ---
        $cmsParent = $createMenu('Konten & Blog', '#cms', 'lucide:newspaper', 'view-cms-dashboard', 50);

        $createMenu('Semua Artikel', 'cms.posts.index', 'lucide:file-text', 'view-posts', 1, $cmsParent);
        $createMenu('Halaman (Pages)', 'cms.pages.index', 'lucide:layers', 'view-pages', 2, $cmsParent);
        $createMenu('Media Library', 'cms.media.index', 'lucide:image', 'view-media', 3, $cmsParent);
        $createMenu('Komentar', 'cms.comments.index', 'lucide:message-square', 'view-comments', 4, $cmsParent);
        $createMenu('Kategori & Tag', 'cms.taxonomy.index', 'lucide:tags', 'view-categories', 5, $cmsParent);

        // --- 6. MARKETING & ANALYTICS ---
        $mktParent = $createMenu('Marketing', '#marketing', 'lucide:megaphone', 'view-analytics-dashboard', 60);

        $createMenu('Statistik Pengunjung', 'marketing.analytics.index', 'lucide:line-chart', 'view-analytics-dashboard', 1, $mktParent);
        $createMenu('Link Shortener', 'marketing.links.index', 'lucide:link', 'manage-marketing-links', 2, $mktParent);
        $createMenu('Iklan (Ads)', 'marketing.ads.index', 'lucide:monitor-play', 'manage-public-ads', 3, $mktParent);

        // --- 7. COMMUNICATION ---
        $chatParent = $createMenu('Komunikasi', '#communication', 'lucide:message-circle', 'view-chats', 70);

        $createMenu('Chat & Diskusi', 'communication.chats.index', 'lucide:messages-square', 'view-chats', 1, $chatParent);
        $createMenu('Siaran (Broadcast)', 'communication.broadcast.index', 'lucide:radio', 'send-broadcast-messages', 2, $chatParent);

        // --- 8. MEMBERSHIP ---
        $memParent = $createMenu('Keanggotaan', '#membership', 'lucide:id-card', 'view-members', 80);

        $createMenu('Data Anggota', 'members.index', 'lucide:contact', 'view-members', 1, $memParent);
        $createMenu('Verifikasi Dokumen', 'members.verification.index', 'lucide:file-check', 'verify-user-documents', 2, $memParent);
        $createMenu('Cetak Kartu', 'members.cards.index', 'lucide:printer', 'print-member-cards', 3, $memParent);

        // --- 9. SYSTEM SETTINGS ---
        $sysParent = $createMenu('Pengaturan Sistem', '#system', 'lucide:settings', 'manage-system-settings', 90);

        $createMenu('Konfigurasi Umum', 'system.settings.general', 'lucide:sliders', 'manage-general-settings', 1, $sysParent);
        $createMenu('Integrasi API', 'system.api.index', 'lucide:webhook', 'manage-api-accounts', 2, $sysParent);
        $createMenu('Backup Database', 'system.backups.index', 'lucide:database-backup', 'manage-database-backups', 3, $sysParent);
        $createMenu('Mode Maintenance', 'system.maintenance.index', 'lucide:construction', 'manage-maintenance-mode', 4, $sysParent);
        $createMenu('Bahasa & Teks', 'system.translations.index', 'lucide:languages', 'manage-translations', 5, $sysParent);
    }
}
