<?php

namespace Database\Seeders;

use App\Models\UrlApi;
use App\Models\UrlAuthenticated;
use App\Models\UrlGroup;
use App\Models\UrlPublic;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // 1. CREATE GROUPS
        // ==========================================

        // --- GROUP AUTHENTICATED (Backend/Dashboard) ---
        $groupDashboard = UrlGroup::firstOrCreate(['slug' => 'dashboard'], ['name' => 'Dasbor', 'section' => 'authenticated', 'is_active' => true, 'order' => 1]);
        $groupCommunication = UrlGroup::firstOrCreate(['slug' => 'communication'], ['name' => 'Komunikasi', 'section' => 'authenticated', 'is_active' => true, 'order' => 2]);
        $groupMembership = UrlGroup::firstOrCreate(['slug' => 'membership'], ['name' => 'Keanggotaan', 'section' => 'authenticated', 'is_active' => true, 'order' => 3]);
        $groupHr = UrlGroup::firstOrCreate(['slug' => 'hr'], ['name' => 'SDM & Pengguna', 'section' => 'authenticated', 'is_active' => true, 'order' => 4]);
        $groupCommerce = UrlGroup::firstOrCreate(['slug' => 'commerce'], ['name' => 'Produk & Layanan', 'section' => 'authenticated', 'is_active' => true, 'order' => 5]);
        $groupFinance = UrlGroup::firstOrCreate(['slug' => 'finance'], ['name' => 'Finansial', 'section' => 'authenticated', 'is_active' => true, 'order' => 6]);
        $groupInventory = UrlGroup::firstOrCreate(['slug' => 'inventory'], ['name' => 'Inventaris', 'section' => 'authenticated', 'is_active' => true, 'order' => 7]);
        $groupCms = UrlGroup::firstOrCreate(['slug' => 'cms'], ['name' => 'Konten & Blog', 'section' => 'authenticated', 'is_active' => true, 'order' => 8]);
        $groupMarketing = UrlGroup::firstOrCreate(['slug' => 'marketing'], ['name' => 'Marketing', 'section' => 'authenticated', 'is_active' => true, 'order' => 9]);
        $groupSystem = UrlGroup::firstOrCreate(['slug' => 'system'], ['name' => 'Sistem', 'section' => 'authenticated', 'is_active' => true, 'order' => 10]);

        // --- GROUP PUBLIC (Frontend) ---
        $groupPublicHome = UrlGroup::firstOrCreate(['slug' => 'public-home'], ['name' => 'Beranda', 'section' => 'public', 'is_active' => true, 'order' => 11]);
        $groupPublicProducts = UrlGroup::firstOrCreate(['slug' => 'public-products'], ['name' => 'Produk & Layanan', 'section' => 'public', 'is_active' => true, 'order' => 12]);
        $groupPublicPartnership = UrlGroup::firstOrCreate(['slug' => 'public-partnership'], ['name' => 'Kemitraan & Kerja Sama', 'section' => 'public', 'is_active' => true, 'order' => 13]);
        $groupPublicCommunity = UrlGroup::firstOrCreate(['slug' => 'public-community'], ['name' => 'Komunitas & Media', 'section' => 'public', 'is_active' => true, 'order' => 14]);
        $groupPublicProfile = UrlGroup::firstOrCreate(['slug' => 'public-profile'], ['name' => 'Profil & Legalitas', 'section' => 'public', 'is_active' => true, 'order' => 15]);
        $groupPublicPolicy = UrlGroup::firstOrCreate(['slug' => 'public-policy'], ['name' => 'Kebijakan & Kepatuhan', 'section' => 'public', 'is_active' => true, 'order' => 16]);
        $groupPublicSupport = UrlGroup::firstOrCreate(['slug' => 'public-support'], ['name' => 'Bantuan & Dukungan', 'section' => 'public', 'is_active' => true, 'order' => 17]);
        $groupPublicApp = UrlGroup::firstOrCreate(['slug' => 'public-app'], ['name' => 'Aplikasi Mobile', 'section' => 'public', 'is_active' => true, 'order' => 18]);

        // ==========================================
        // 2. HELPER FUNCTIONS
        // ==========================================

        $getPermId = function ($name) {
            $permission = Permission::where('name', $name)->first();
            return $permission ? $permission->id : null;
        };

        $createMenu = function ($group, $name, $url, $route, $icon, $perm, $order, $parent = null) use ($getPermId) {
            return UrlAuthenticated::firstOrCreate(
                ['url' => $url, 'group_id' => $group->id],
                [
                    'name' => $name,
                    'route' => $route,
                    'icon' => $icon,
                    'order' => $order,
                    'parent_id' => $parent?->id,
                    'permission_id' => $getPermId($perm),
                    'is_active' => true,
                    'badge' => null,
                    'badge_color' => null,
                    'hint' => null,
                ]
            );
        };

        // Updated Helper for Public Menu with Target and Active State
        $createPublicMenu = function ($group, $name, $url, $icon, $description, $color, $target, $order, $parent = null, $isActive = true) {
            // Uniqueness now includes target to allow duplicates in different locations (e.g. Navbar vs Footer)
            return UrlPublic::firstOrCreate(
                ['url' => $url, 'group_id' => $group->id, 'target' => $target, 'name' => $name],
                [
                    'icon' => $icon,
                    'description' => $description,
                    'color' => $color,
                    'hint' => null,
                    'order' => $order,
                    'parent_id' => $parent?->id,
                    'is_active' => $isActive,
                ]
            );
        };

        $createApi = function ($group, $name, $url, $method, $perm, $isPublic = false) use ($getPermId) {
            return UrlApi::firstOrCreate(
                ['name' => $name, 'group_id' => $group->id],
                [
                    'url' => $url,
                    'method' => $method,
                    'permission_id' => $getPermId($perm),
                    'is_active' => true,
                    'is_public' => $isPublic,
                    'route' => null,
                ]
            );
        };

        // ==========================================
        // 3. SEED AUTHENTICATED MENUS
        // ==========================================

        // --- GROUP DASHBOARD ---
        $createMenu($groupDashboard, 'Dashboard Utama', '/dashboard', 'dashboard', 'lucide:layout-dashboard', 'view-users', 1);
        $createMenu($groupDashboard, 'Dashboard Keuangan', '/finance', 'finance.dashboard', 'lucide:pie-chart', 'view-finance-dashboard', 2);
        $createMenu($groupDashboard, 'Dashboard Penjualan', '/commerce', 'commerce.dashboard', 'lucide:bar-chart-4', 'view-commerce-dashboard', 3);
        $createMenu($groupDashboard, 'Dashboard Gudang', '/inventory', 'inventory.dashboard', 'lucide:layout-grid', 'view-inventory-dashboard', 4);
        $createMenu($groupDashboard, 'Dashboard CMS', '/cms', 'cms.dashboard', 'lucide:layout', 'view-cms-dashboard', 5);
        $createMenu($groupDashboard, 'Dashboard Sistem', '/system/dashboard', 'super.dashboard', 'lucide:server', 'view-system-dashboard', 6);

        // --- GROUP KOMUNIKASI ---
        $createMenu($groupCommunication, 'Chat & Diskusi', '/communication/chats', 'communication.chats.index', 'lucide:messages-square', 'view-chats', 1);
        $createMenu($groupCommunication, 'Siaran', '/communication/broadcast', 'communication.broadcast.index', 'lucide:radio', 'send-broadcast-messages', 2);

        // --- GROUP KEANGGOTAAN ---
        $createMenu($groupMembership, 'Data Anggota', '/members', 'members.index', 'lucide:contact', 'view-members', 1);
        $createMenu($groupMembership, 'Verifikasi Dokumen', '/members/verification', 'members.verification.index', 'lucide:file-check', 'verify-user-documents', 2);
        $createMenu($groupMembership, 'Cetak Kartu', '/members/cards', 'members.cards.index', 'lucide:printer', 'print-member-cards', 3);

        // --- GROUP SDM & PENGGUNA ---
        $createMenu($groupHr, 'Daftar Pengguna', '/users', 'users.index', 'lucide:user', 'view-users', 1);
        $roleMenu = $createMenu($groupHr, 'Role & Hak Akses', '#roles', null, 'lucide:shield-check', 'view-roles', 2);
        $createMenu($groupHr, 'Roles', '/roles', 'super.roles.index', 'lucide:shield', 'view-roles', 1, $roleMenu);
        $createMenu($groupHr, 'Hak Akses', '/permissions', 'super.permissions.index', 'lucide:key', 'view-permissions', 2, $roleMenu);
        $createMenu($groupHr, 'Role & Pengguna', '/users/roles', 'users.roles.index', 'lucide:user-cog', 'assign-roles', 3, $roleMenu);
        $createMenu($groupHr, 'Hak Akses Pengguna', '/users/permissions', 'users.permissions.index', 'lucide:user-check', 'manage-permissions', 4, $roleMenu);
        $createMenu($groupHr, 'Divisi & Jabatan', '/divisions', 'divisions.index', 'lucide:network', 'view-divisions', 3);
        $createMenu($groupHr, 'Log Aktivitas', '/logs', 'super.logs.index', 'lucide:activity', 'view-activity-logs', 4);

        // --- GROUP PRODUK & LAYANAN ---
        $createMenu($groupCommerce, 'Pesanan', '/commerce/orders', 'commerce.orders.index', 'lucide:shopping-cart', 'view-orders', 1);
        $createMenu($groupCommerce, 'Produk', '/commerce/products', 'commerce.products.index', 'lucide:tag', 'view-products', 2);
        $createMenu($groupCommerce, 'Layanan Jasa', '/commerce/services', 'commerce.services.index', 'lucide:briefcase', 'view-services', 3);
        $createMenu($groupCommerce, 'Ulasan Pelanggan', '/commerce/reviews', 'commerce.reviews.index', 'lucide:star', 'view-reviews', 4);
        $createMenu($groupCommerce, 'Kupon & Diskon', '/commerce/coupons', 'commerce.coupons.index', 'lucide:ticket', 'manage-discounts', 5);

        // --- GROUP FINANSIAL ---
        $createMenu($groupFinance, 'Catatan Transaksi', '/finance/records', 'finance.records.index', 'lucide:receipt', 'view-finance-records', 1);
        $createMenu($groupFinance, 'Penggajian', '/finance/payroll', 'finance.payroll.index', 'lucide:wallet', 'view-payrolls', 2);
        $createMenu($groupFinance, 'Anggaran', '/finance/budgets', 'finance.budgets.index', 'lucide:piggy-bank', 'view-budgets', 3);
        $createMenu($groupFinance, 'Laporan Keuangan', '/finance/reports', 'finance.reports.index', 'lucide:file-bar-chart', 'view-finance-reports', 4);
        $createMenu($groupFinance, 'Chart of Accounts', '/finance/categories', 'finance.categories.index', 'lucide:list-tree', 'view-finance-categories', 5);

        // --- GROUP INVENTARIS ---
        $createMenu($groupInventory, 'Stok Barang', '/inventory/items', 'inventory.items.index', 'lucide:boxes', 'view-inventory-items', 1);
        $createMenu($groupInventory, 'Barang Masuk', '/inventory/in', 'inventory.in.index', 'lucide:arrow-down-to-line', 'create-inventory-transaction-in', 2);
        $createMenu($groupInventory, 'Barang Keluar', '/inventory/out', 'inventory.out.index', 'lucide:arrow-up-from-line', 'create-inventory-transaction-out', 3);
        $createMenu($groupInventory, 'Peminjaman Aset', '/inventory/loans', 'inventory.loans.index', 'lucide:hand-platter', 'view-inventory-loans', 4);
        $createMenu($groupInventory, 'Lokasi Gudang', '/inventory/warehouses', 'inventory.warehouses.index', 'lucide:warehouse', 'view-warehouses', 5);
        $createMenu($groupInventory, 'Opname Stok', '/inventory/adjustments', 'inventory.adjustments.index', 'lucide:clipboard-check', 'adjust-inventory-stock', 6);

        // --- GROUP KONTEN & BLOG ---
        $createMenu($groupCms, 'Semua Artikel', '/cms/posts', 'cms.posts.index', 'lucide:file-text', 'view-posts', 1);
        $createMenu($groupCms, 'Halaman', '/cms/pages', 'cms.pages.index', 'lucide:layers', 'view-pages', 2);
        $createMenu($groupCms, 'Media Library', '/cms/media', 'cms.media.index', 'lucide:image', 'view-media', 3);
        $createMenu($groupCms, 'Komentar', '/cms/comments', 'cms.comments.index', 'lucide:message-square', 'view-comments', 4);
        $createMenu($groupCms, 'Kategori & Tag', '/cms/taxonomy', 'cms.taxonomy.index', 'lucide:tags', 'view-categories', 5);

        // --- GROUP MARKETING ---
        $createMenu($groupMarketing, 'Statistik Pengunjung', '/marketing/analytics', 'marketing.analytics.index', 'lucide:line-chart', 'view-analytics-dashboard', 1);
        $createMenu($groupMarketing, 'Link Shortener', '/marketing/links', 'marketing.links.index', 'lucide:link', 'manage-marketing-links', 2);
        $createMenu($groupMarketing, 'Iklan', '/marketing/ads', 'marketing.ads.index', 'lucide:monitor-play', 'manage-public-ads', 3);

        // --- GROUP SISTEM ---
        $createMenu($groupSystem, 'Konfigurasi Umum', '/settings', 'super.settings.index', 'lucide:sliders', 'manage-general-settings', 1);
        $createMenu($groupSystem, 'Integrasi API', '/api-accounts', 'super.api-accounts.index', 'lucide:webhook', 'manage-api-accounts', 2);
        $createMenu($groupSystem, 'Backup Database', '/backups', 'system.backups.index', 'lucide:database-backup', 'manage-database-backups', 3);
        $createMenu($groupSystem, 'Mode Maintenance', '/maintenance', 'system.maintenance.index', 'lucide:construction', 'manage-maintenance-mode', 4);
        $createMenu($groupSystem, 'Bahasa & Teks', '/translations', 'system.translations.index', 'lucide:languages', 'manage-translations', 5);

        // ==========================================
        // 4. SEED PUBLIC MENUS
        // ==========================================

        // ---------------------------------------------------------------------
        // TOP BAR (Kebijakan & Bantuan)
        // ---------------------------------------------------------------------

        // 6. Kebijakan & Kepatuhan (Top Bar)
        $menuKebijakanTop = $createPublicMenu($groupPublicPolicy, 'Kebijakan', '#', 'lucide:shield', 'Aturan dan kebijakan penggunaan', 'text-red-600', 'topbar', 1);
        $createPublicMenu($groupPublicPolicy, 'Kebijakan Privasi', '/privacy-policy', 'lucide:lock', 'Bagaimana kami menjaga data Anda', 'text-slate-700', 'topbar', 1, $menuKebijakanTop);
        $createPublicMenu($groupPublicPolicy, 'Syarat & Ketentuan', '/terms-conditions', 'lucide:file-check', 'Syarat penggunaan layanan kami', 'text-slate-700', 'topbar', 2, $menuKebijakanTop);
        $createPublicMenu($groupPublicPolicy, 'Kebijakan Pengembalian', '/refund-policy', 'lucide:refresh-ccw', 'Prosedur pengembalian dana dan barang', 'text-orange-600', 'topbar', 3, $menuKebijakanTop);

        // 7. Bantuan & Dukungan (Top Bar)
        $menuBantuanTop = $createPublicMenu($groupPublicSupport, 'Bantuan', '#', 'lucide:help-circle', 'Dapatkan bantuan yang Anda butuhkan', 'text-yellow-500', 'topbar', 2);
        $createPublicMenu($groupPublicSupport, 'FAQ', '/faq', 'lucide:help-circle', 'Pertanyaan yang sering diajukan', 'text-yellow-600', 'topbar', 1, $menuBantuanTop);
        $createPublicMenu($groupPublicSupport, 'Hubungi Kami', '/contact', 'lucide:phone', 'Kontak layanan pelanggan', 'text-green-600', 'topbar', 2, $menuBantuanTop);
        $createPublicMenu($groupPublicSupport, 'Tiket Bantuan', '/support/ticket', 'lucide:ticket', 'Buat tiket untuk masalah teknis', 'text-purple-600', 'topbar', 3, $menuBantuanTop);

        // ---------------------------------------------------------------------
        // NAVBAR (Menu Utama)
        // ---------------------------------------------------------------------

        // 1. Beranda
        $createPublicMenu($groupPublicHome, 'Beranda', '/', 'lucide:home', 'Halaman utama', 'text-blue-500', 'navbar', 1);

        // 2. Produk & Layanan (Parent)
        $menuProdukLayanan = $createPublicMenu($groupPublicProducts, 'Produk & Layanan', '#', 'lucide:package', 'Katalog produk dan jasa kami', 'text-indigo-600', 'navbar', 2);

        // Submenu
        $createPublicMenu($groupPublicProducts, 'Produk', '/product', 'lucide:shopping-bag', 'Jelajahi berbagai produk berkualitas', 'text-purple-500', 'navbar', 1, $menuProdukLayanan);
        $createPublicMenu($groupPublicProducts, 'Layanan', '/service', 'lucide:wrench', 'Solusi profesional untuk kebutuhan Anda', 'text-slate-500', 'navbar', 2, $menuProdukLayanan);
        $createPublicMenu($groupPublicProducts, 'Penyewaan', '/rent', 'lucide:key', 'Sewa peralatan dan aset dengan mudah', 'text-orange-500', 'navbar', 3, $menuProdukLayanan);
        $createPublicMenu($groupPublicProducts, 'Event Organizer', '/eo', 'lucide:calendar-heart', 'Jasa penyelenggaraan acara terpercaya', 'text-pink-500', 'navbar', 4, $menuProdukLayanan);
        $createPublicMenu($groupPublicProducts, 'Jadwal Acara', '/schedule', 'lucide:calendar-days', 'Kalender kegiatan dan event mendatang', 'text-teal-500', 'navbar', 5, $menuProdukLayanan);

        // 3. Kemitraan & Kerja Sama
        $menuKemitraan = $createPublicMenu($groupPublicPartnership, 'Kemitraan', '#', 'lucide:handshake', 'Bergabung menjadi mitra kami', 'text-cyan-600', 'navbar', 3);
        $createPublicMenu($groupPublicPartnership, 'Peluang Kemitraan', '/partnership/opportunities', 'lucide:briefcase', 'Temukan peluang bisnis bersama', 'text-cyan-500', 'navbar', 1, $menuKemitraan);
        $createPublicMenu($groupPublicPartnership, 'Daftar Mitra', '/partnership/list', 'lucide:users', 'Lihat siapa saja mitra kami', 'text-blue-400', 'navbar', 2, $menuKemitraan);
        $createPublicMenu($groupPublicPartnership, 'Program Afiliasi', '/partnership/affiliate', 'lucide:link', 'Dapatkan komisi dengan berbagi', 'text-emerald-500', 'navbar', 3, $menuKemitraan);

        // 4. Komunitas & Media
        $menuKomunitas = $createPublicMenu($groupPublicCommunity, 'Komunitas', '#', 'lucide:newspaper', 'Berita dan komunitas terkini', 'text-green-600', 'navbar', 4);
        $createPublicMenu($groupPublicCommunity, 'Berita & Artikel', '/news', 'lucide:file-text', 'Baca artikel terbaru seputar industri', 'text-gray-600', 'navbar', 1, $menuKomunitas);
        $createPublicMenu($groupPublicCommunity, 'Galeri Foto', '/gallery', 'lucide:image', 'Dokumentasi kegiatan kami', 'text-rose-500', 'navbar', 2, $menuKomunitas);
        $createPublicMenu($groupPublicCommunity, 'Forum Diskusi', '/forum', 'lucide:message-circle', 'Diskusikan topik menarik bersama komunitas', 'text-violet-500', 'navbar', 3, $menuKomunitas);
        $createPublicMenu($groupPublicCommunity, 'Testimoni', '/testimonials', 'lucide:message-square-quote', 'Apa kata mereka tentang kami', 'text-amber-500', 'navbar', 4, $menuKomunitas);

        // 5. Profil & Legalitas
        $menuProfil = $createPublicMenu($groupPublicProfile, 'Tentang Kami', '#', 'lucide:info', 'Informasi lengkap perusahaan', 'text-blue-700', 'navbar', 5);
        $createPublicMenu($groupPublicProfile, 'Profil Perusahaan', '/about', 'lucide:building-2', 'Sejarah dan latar belakang perusahaan', 'text-blue-600', 'navbar', 1, $menuProfil);
        $createPublicMenu($groupPublicProfile, 'Visi & Misi', '/vision-mission', 'lucide:target', 'Tujuan dan arah masa depan kami', 'text-red-500', 'navbar', 2, $menuProfil);
        $createPublicMenu($groupPublicProfile, 'Struktur Organisasi', '/organization', 'lucide:sitemap', 'Susunan tim manajemen kami', 'text-slate-600', 'navbar', 3, $menuProfil);
        $createPublicMenu($groupPublicProfile, 'Legalitas & Sertifikasi', '/legality', 'lucide:award', 'Dokumen resmi dan sertifikasi', 'text-yellow-600', 'navbar', 4, $menuProfil);

        // ---------------------------------------------------------------------
        // FOOTER (Link Tambahan & Duplikasi)
        // ---------------------------------------------------------------------

        // Duplikasi Menu Utama untuk Footer
        $createPublicMenu($groupPublicHome, 'Beranda', '/', 'lucide:home', 'Kembali ke atas', 'text-blue-500', 'footer', 1);
        $createPublicMenu($groupPublicProducts, 'Produk & Layanan', '/product', 'lucide:package', 'Lihat semua layanan', 'text-indigo-600', 'footer', 2);
        $createPublicMenu($groupPublicPartnership, 'Peluang Kemitraan', '/partnership/opportunities', 'lucide:handshake', 'Gabung mitra', 'text-cyan-600', 'footer', 3);
        $createPublicMenu($groupPublicCommunity, 'Berita Terkini', '/news', 'lucide:newspaper', 'Baca berita', 'text-green-600', 'footer', 4);

        // Kebijakan & Bantuan di Footer juga (Standard Practice)
        $createPublicMenu($groupPublicPolicy, 'Kebijakan Privasi', '/privacy-policy', 'lucide:lock', null, 'text-slate-500', 'footer', 5);
        $createPublicMenu($groupPublicPolicy, 'Syarat & Ketentuan', '/terms-conditions', 'lucide:file-check', null, 'text-slate-500', 'footer', 6);
        $createPublicMenu($groupPublicSupport, 'Hubungi Kami', '/contact', 'lucide:phone', null, 'text-slate-500', 'footer', 7);
        $createPublicMenu($groupPublicSupport, 'FAQ', '/faq', 'lucide:help-circle', null, 'text-slate-500', 'footer', 8);

        // Aplikasi Mobile (Inactive by default)
        $menuApp = $createPublicMenu($groupPublicApp, 'Download Aplikasi', '#', 'lucide:smartphone', 'Unduh aplikasi kami', 'text-slate-800', 'footer', 9, null, false);
        $createPublicMenu($groupPublicApp, 'Google Play Store', 'https://play.google.com', 'lucide:play', 'Untuk Android', 'text-green-500', 'footer', 1, $menuApp, false);
        $createPublicMenu($groupPublicApp, 'App Store', 'https://apple.com', 'lucide:apple', 'Untuk iOS', 'text-slate-900', 'footer', 2, $menuApp, false);

        // ==========================================
        // 5. SEED API ROUTES
        // ==========================================

        $createApi($groupHr, 'api.users.index', '/api/v1/users', 'GET', 'view-users');
        $createApi($groupHr, 'api.users.store', '/api/v1/users', 'POST', 'create-users');
        $createApi($groupHr, 'api.users.show', '/api/v1/users/{user}', 'GET', 'view-users');
        $createApi($groupHr, 'api.users.update', '/api/v1/users/{user}', 'PUT', 'edit-users');
        $createApi($groupHr, 'api.users.destroy', '/api/v1/users/{user}', 'DELETE', 'delete-users');

        $createApi($groupHr, 'api.roles.index', '/api/v1/roles', 'GET', 'view-roles');
        $createApi($groupHr, 'api.roles.store', '/api/v1/roles', 'POST', 'create-roles');
        $createApi($groupHr, 'api.roles.update', '/api/v1/roles/{role}', 'PUT', 'edit-roles');
        $createApi($groupHr, 'api.roles.destroy', '/api/v1/roles/{role}', 'DELETE', 'delete-roles');

        $createApi($groupFinance, 'api.finance.records.index', '/api/v1/finance/records', 'GET', 'view-finance-records');
        $createApi($groupFinance, 'api.finance.records.store', '/api/v1/finance/records', 'POST', 'create-finance-records');
        $createApi($groupFinance, 'api.finance.records.update', '/api/v1/finance/records/{record}', 'PUT', 'edit-finance-records');
        $createApi($groupFinance, 'api.finance.records.destroy', '/api/v1/finance/records/{record}', 'DELETE', 'delete-finance-records');

        $createApi($groupInventory, 'api.inventory.items.index', '/api/v1/inventory/items', 'GET', 'view-inventory-items');
        $createApi($groupInventory, 'api.inventory.items.store', '/api/v1/inventory/items', 'POST', 'create-inventory-items');
        $createApi($groupInventory, 'api.inventory.items.update', '/api/v1/inventory/items/{item}', 'PUT', 'edit-inventory-items');
        $createApi($groupInventory, 'api.inventory.items.destroy', '/api/v1/inventory/items/{item}', 'DELETE', 'delete-inventory-items');

        $createApi($groupCommerce, 'api.products.index', '/api/v1/commerce/products', 'GET', 'view-products');
        $createApi($groupCommerce, 'api.products.store', '/api/v1/commerce/products', 'POST', 'create-products');
        $createApi($groupCommerce, 'api.products.update', '/api/v1/commerce/products/{product}', 'PUT', 'edit-products');
        $createApi($groupCommerce, 'api.products.destroy', '/api/v1/commerce/products/{product}', 'DELETE', 'delete-products');

        $createApi($groupCommerce, 'api.orders.index', '/api/v1/commerce/orders', 'GET', 'view-orders');
        $createApi($groupCommerce, 'api.orders.store', '/api/v1/commerce/orders', 'POST', 'create-orders');
        $createApi($groupCommerce, 'api.orders.update', '/api/v1/commerce/orders/{order}', 'PUT', 'edit-orders');

        $createApi($groupCms, 'api.posts.index', '/api/v1/cms/posts', 'GET', 'view-posts');
        $createApi($groupCms, 'api.posts.store', '/api/v1/cms/posts', 'POST', 'create-posts');
        $createApi($groupCms, 'api.posts.update', '/api/v1/cms/posts/{post}', 'PUT', 'edit-posts');
        $createApi($groupCms, 'api.posts.destroy', '/api/v1/cms/posts/{post}', 'DELETE', 'delete-posts');

        $createApi($groupSystem, 'api.auth.login', '/api/v1/auth/login', 'POST', null, true);
        $createApi($groupSystem, 'api.auth.register', '/api/v1/auth/register', 'POST', null, true);
        $createApi($groupSystem, 'api.auth.logout', '/api/v1/auth/logout', 'POST', null, false);
        $createApi($groupSystem, 'api.user.profile', '/api/v1/user/profile', 'GET', null, false);
    }
}
