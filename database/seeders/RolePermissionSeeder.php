<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Define Huge List of Permissions
        $permissions = $this->getPermissionsList();

        // Insert permissions in chunks to avoid memory issues if too large
        $permissionData = collect($permissions)->map(function ($name) {
            return [
                'name' => $name,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        });

        // Gunakan insert untuk performa (karena datanya banyak)
        // Kita chunk per 100 agar aman
        foreach ($permissionData->chunk(100) as $chunk) {
            Permission::insertOrIgnore($chunk->toArray());
        }

        // 3. Create Roles & Assign Permissions
        $this->createRoles();
    }

    private function createRoles()
    {
        // --- 1. SUPER ADMIN (God Mode) ---
        // Memiliki semua akses via Gate::before di AppServiceProvider
        Role::firstOrCreate(['name' => 'super-admin']);

        // --- 2. ADMIN (Manager Operasional) ---
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());
        // Admin tidak boleh menyentuh konfigurasi sistem kritis
        $admin->revokePermissionTo([
            'manage-system-settings', 'manage-database-backups', 'view-system-logs',
            'manage-api-accounts', 'delete-roles'
        ]);

        // --- 3. FINANCE MANAGER (Keuangan) ---
        $financeManager = Role::firstOrCreate(['name' => 'finance-manager']);
        $financeManager->givePermissionTo([
            'view-finance-dashboard',
            ...$this->filterPermissions('finance'),
            ...$this->filterPermissions('payroll'),
            ...$this->filterPermissions('budget'),
            'view-users', 'view-divisions', 'view-positions'
        ]);

        // --- 4. HR MANAGER (SDM & Kepegawaian) ---
        $hrManager = Role::firstOrCreate(['name' => 'hr-manager']);
        $hrManager->givePermissionTo([
            ...$this->filterPermissions('user'),
            ...$this->filterPermissions('payroll'),
            ...$this->filterPermissions('division'),
            ...$this->filterPermissions('position'),
            ...$this->filterPermissions('badge'),
            'view-attendance', 'approve-leaves',
        ]);

        // --- 5. INVENTORY MANAGER (Kepala Gudang) ---
        $invManager = Role::firstOrCreate(['name' => 'inventory-manager']);
        $invManager->givePermissionTo([
            'view-inventory-dashboard',
            ...$this->filterPermissions('inventory'),
            ...$this->filterPermissions('warehouse'),
            ...$this->filterPermissions('supplier'),
            'view-products'
        ]);

        // --- 6. WAREHOUSE STAFF (Staf Gudang) ---
        $whStaff = Role::firstOrCreate(['name' => 'warehouse-staff']);
        $whStaff->givePermissionTo([
            'view-inventory-items', 'create-inventory-transaction-in', 'create-inventory-transaction-out',
            'view-stock-movements', 'view-warehouses', 'scan-inventory-qr'
        ]);

        // --- 7. SALES MANAGER (Penjualan & Toko) ---
        $salesManager = Role::firstOrCreate(['name' => 'sales-manager']);
        $salesManager->givePermissionTo([
            'view-commerce-dashboard',
            ...$this->filterPermissions('product'),
            ...$this->filterPermissions('order'),
            ...$this->filterPermissions('customer'),
            ...$this->filterPermissions('marketing'),
            'view-inventory-items' // Cek stok
        ]);

        // --- 8. CONTENT EDITOR (CMS & Blog) ---
        $editor = Role::firstOrCreate(['name' => 'content-editor']);
        $editor->givePermissionTo([
            'view-cms-dashboard',
            ...$this->filterPermissions('post'),
            ...$this->filterPermissions('page'),
            ...$this->filterPermissions('media'),
            ...$this->filterPermissions('comment'),
            ...$this->filterPermissions('tag'),
            ...$this->filterPermissions('category'),
        ]);

        // --- 9. ACCOUNTANT (Auditor) ---
        $accountant = Role::firstOrCreate(['name' => 'accountant']);
        $accountant->givePermissionTo([
            'view-finance-dashboard',
            'view-finance-records', 'view-finance-reports', 'export-finance-reports',
            'view-payrolls', 'view-budgets',
            'verify-finance-records', 'audit-finance-records'
        ]);

        // --- 10. VENDOR (Penyedia Layanan) ---
        $vendor = Role::firstOrCreate(['name' => 'vendor']);
        $vendor->givePermissionTo([
            'create-services', 'edit-own-services', 'delete-own-services',
            'view-own-orders', 'process-own-orders',
            'view-own-finance'
        ]);

        // --- 11. MEMBER (User Biasa) ---
        $member = Role::firstOrCreate(['name' => 'member']);
        $member->givePermissionTo([
            'view-own-profile', 'edit-own-profile',
            'create-orders', 'view-own-orders',
            'create-comments', 'view-public-posts'
        ]);
    }

    private function getPermissionsList(): array
    {
        return [
            // --- CORE: USER MANAGEMENT (20) ---
            'view-users', 'create-users', 'edit-users', 'delete-users', 'restore-users', 'force-delete-users',
            'view-user-details', 'edit-user-details', 'approve-user-registration', 'ban-users',
            'view-roles', 'create-roles', 'edit-roles', 'delete-roles', 'assign-roles',
            'view-permissions', 'manage-permissions',
            'view-activity-logs', 'delete-activity-logs', 'export-activity-logs',

            // --- ORGANIZATION (16) ---
            'view-divisions', 'create-divisions', 'edit-divisions', 'delete-divisions',
            'view-positions', 'create-positions', 'edit-positions', 'delete-positions',
            'view-badges', 'create-badges', 'edit-badges', 'delete-badges', 'assign-badges',
            'view-attendance', 'view-leave-requests', 'approve-leaves', 'manage-holidays',
            'view-teams', 'create-teams', 'edit-teams',

            // --- FINANCE: ACCOUNTING (24) ---
            'view-finance-dashboard',
            'view-finance-records', 'create-finance-records', 'edit-finance-records', 'delete-finance-records',
            'verify-finance-records', 'audit-finance-records', 'void-finance-records',
            'view-finance-categories', 'create-finance-categories', 'edit-finance-categories', 'delete-finance-categories',
            'view-finance-reports', 'export-finance-reports', 'print-finance-reports',
            'manage-tax-settings', 'manage-currency-settings',
            'view-own-finance', // Untuk Vendor/Member

            // --- FINANCE: BUDGET & PAYROLL (18) ---
            'view-budgets', 'create-budgets', 'edit-budgets', 'delete-budgets', 'approve-budgets',
            'view-payrolls', 'create-payrolls', 'edit-payrolls', 'delete-payrolls',
            'approve-payrolls', 'process-payrolls', 'print-payrolls',
            'view-own-payroll', 'view-all-payrolls',
            'manage-salary-components', 'manage-deductions',

            // --- INVENTORY: CORE (28) ---
            'view-inventory-dashboard',
            'view-inventory-items', 'create-inventory-items', 'edit-inventory-items', 'delete-inventory-items',
            'view-inventory-categories', 'create-inventory-categories', 'edit-inventory-categories', 'delete-inventory-categories',
            'view-warehouses', 'create-warehouses', 'edit-warehouses', 'delete-warehouses',
            'view-suppliers', 'create-suppliers', 'edit-suppliers', 'delete-suppliers',
            'print-inventory-labels', 'scan-inventory-qr',
            'adjust-inventory-stock', // Stock Opname
            'view-stock-alerts',

            // --- INVENTORY: TRANSACTIONS (15) ---
            'view-stock-movements',
            'create-inventory-transaction-in', // Barang Masuk
            'create-inventory-transaction-out', // Barang Keluar
            'create-inventory-transaction-transfer', // Transfer Gudang
            'approve-inventory-transfer',
            'view-inventory-loans', 'create-inventory-loans', 'approve-inventory-loans', 'return-inventory-loans',
            'manage-inventory-write-offs', // Penghapusan Aset

            // --- COMMERCE: PRODUCTS & SERVICES (25) ---
            'view-commerce-dashboard',
            'view-products', 'create-products', 'edit-products', 'delete-products',
            'view-product-categories', 'create-product-categories', 'edit-product-categories', 'delete-product-categories',
            'view-product-variants', 'manage-product-stock',
            'view-services', 'create-services', 'edit-services', 'delete-services',
            'edit-own-services', 'delete-own-services', // Vendor Specific
            'manage-discounts', 'manage-coupons',

            // --- COMMERCE: ORDERS (20) ---
            'view-orders', 'create-orders', 'edit-orders', 'delete-orders',
            'view-own-orders', 'process-own-orders', // Vendor/Member
            'approve-orders', 'process-orders', 'ship-orders', 'complete-orders', 'cancel-orders', 'refund-orders',
            'print-invoices', 'print-shipping-labels',
            'view-reviews', 'approve-reviews', 'reply-reviews', 'delete-reviews',
            'manage-shipping-rates', 'manage-payment-gateways',

            // --- CMS: CONTENT (30) ---
            'view-cms-dashboard',
            'view-posts', 'create-posts', 'edit-posts', 'delete-posts',
            'edit-others-posts', 'delete-others-posts',
            'publish-posts', 'archive-posts',
            'view-pages', 'create-pages', 'edit-pages', 'delete-pages', 'publish-pages',
            'view-categories', 'create-categories', 'edit-categories', 'delete-categories',
            'view-tags', 'create-tags', 'edit-tags', 'delete-tags',
            'view-comments', 'approve-comments', 'reply-comments', 'delete-comments',
            'view-media', 'upload-media', 'delete-media',

            // --- MARKETING & ANALYTICS (15) ---
            'view-analytics-dashboard',
            'manage-marketing-links', 'view-link-stats',
            'manage-public-trackers', 'manage-public-ads', 'manage-public-scripts',
            'view-visitor-logs', 'export-analytics-data',
            'manage-seo-settings', 'manage-sitemap',

            // --- COMMUNICATION (10) ---
            'view-chats', 'create-chat-groups', 'edit-chat-groups', 'delete-chat-groups',
            'send-broadcast-messages', 'moderate-chat', 'view-chat-reports',
            'create-comments', 'view-public-posts',
            'manage-chat-settings',

            // --- SYSTEM & SETTINGS (20) ---
            'view-system-dashboard',
            'manage-system-settings', 'manage-general-settings',
            'manage-finance-settings', 'manage-inventory-settings', 'manage-commerce-settings',
            'manage-membership-settings', 'manage-email-settings',
            'manage-api-accounts', 'view-api-logs',
            'manage-maintenance-mode',
            'manage-database-backups', 'restore-database-backups',
            'manage-translations', 'sync-translations',
            'manage-files', 'clean-temp-files',

            // --- MEMBERSHIP & VERIFICATION (10) ---
            'view-members', 'approve-members', 'reject-members',
            'print-member-cards', 'generate-member-id',
            'view-own-profile', 'edit-own-profile',
            'verify-user-documents',
        ];
    }

    /**
     * Helper untuk memfilter permission berdasarkan kata kunci
     */
    private function filterPermissions($keyword)
    {
        return array_filter($this->getPermissionsList(), function ($perm) use ($keyword) {
            return str_contains($perm, $keyword);
        });
    }
}
