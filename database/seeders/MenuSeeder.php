<?php

namespace Database\Seeders;

use App\Models\UrlApi;
use App\Models\UrlAuthenticated;
use App\Models\UrlGroup;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
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
                ]
            );
        };

        // --- GROUP DASHBOARD (Semua Dashboard dikumpulkan di sini) ---
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
        // (Dashboard Penjualan dipindah ke atas)
        $createMenu($groupCommerce, 'Pesanan', '/commerce/orders', 'commerce.orders.index', 'lucide:shopping-cart', 'view-orders', 1);
        $createMenu($groupCommerce, 'Produk', '/commerce/products', 'commerce.products.index', 'lucide:tag', 'view-products', 2);
        $createMenu($groupCommerce, 'Layanan Jasa', '/commerce/services', 'commerce.services.index', 'lucide:briefcase', 'view-services', 3);
        $createMenu($groupCommerce, 'Ulasan Pelanggan', '/commerce/reviews', 'commerce.reviews.index', 'lucide:star', 'view-reviews', 4);
        $createMenu($groupCommerce, 'Kupon & Diskon', '/commerce/coupons', 'commerce.coupons.index', 'lucide:ticket', 'manage-discounts', 5);

        // --- GROUP FINANSIAL ---
        // (Dashboard Keuangan dipindah ke atas)
        $createMenu($groupFinance, 'Catatan Transaksi', '/finance/records', 'finance.records.index', 'lucide:receipt', 'view-finance-records', 1);
        $createMenu($groupFinance, 'Penggajian', '/finance/payroll', 'finance.payroll.index', 'lucide:wallet', 'view-payrolls', 2);
        $createMenu($groupFinance, 'Anggaran', '/finance/budgets', 'finance.budgets.index', 'lucide:piggy-bank', 'view-budgets', 3);
        $createMenu($groupFinance, 'Laporan Keuangan', '/finance/reports', 'finance.reports.index', 'lucide:file-bar-chart', 'view-finance-reports', 4);
        $createMenu($groupFinance, 'Chart of Accounts', '/finance/categories', 'finance.categories.index', 'lucide:list-tree', 'view-finance-categories', 5);

        // --- GROUP INVENTARIS ---
        // (Dashboard Gudang dipindah ke atas)
        $createMenu($groupInventory, 'Stok Barang', '/inventory/items', 'inventory.items.index', 'lucide:boxes', 'view-inventory-items', 1);
        $createMenu($groupInventory, 'Barang Masuk', '/inventory/in', 'inventory.in.index', 'lucide:arrow-down-to-line', 'create-inventory-transaction-in', 2);
        $createMenu($groupInventory, 'Barang Keluar', '/inventory/out', 'inventory.out.index', 'lucide:arrow-up-from-line', 'create-inventory-transaction-out', 3);
        $createMenu($groupInventory, 'Peminjaman Aset', '/inventory/loans', 'inventory.loans.index', 'lucide:hand-platter', 'view-inventory-loans', 4);
        $createMenu($groupInventory, 'Lokasi Gudang', '/inventory/warehouses', 'inventory.warehouses.index', 'lucide:warehouse', 'view-warehouses', 5);
        $createMenu($groupInventory, 'Opname Stok', '/inventory/adjustments', 'inventory.adjustments.index', 'lucide:clipboard-check', 'adjust-inventory-stock', 6);

        // --- GROUP KONTEN & BLOG ---
        // (Dashboard CMS dipindah ke atas)
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
        // (Dashboard Sistem dipindah ke atas)
        $createMenu($groupSystem, 'Konfigurasi Umum', '/settings', 'super.settings.index', 'lucide:sliders', 'manage-general-settings', 1);
        $createMenu($groupSystem, 'Integrasi API', '/api-accounts', 'super.api-accounts.index', 'lucide:webhook', 'manage-api-accounts', 2);
        $createMenu($groupSystem, 'Backup Database', '/backups', 'system.backups.index', 'lucide:database-backup', 'manage-database-backups', 3);
        $createMenu($groupSystem, 'Mode Maintenance', '/maintenance', 'system.maintenance.index', 'lucide:construction', 'manage-maintenance-mode', 4);
        $createMenu($groupSystem, 'Bahasa & Teks', '/translations', 'system.translations.index', 'lucide:languages', 'manage-translations', 5);

        // --- API ROUTES ---
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
