<?php

use App\Http\Controllers\Super\ApiManagementController;
use App\Http\Controllers\Super\DashboardController;
use App\Http\Controllers\Super\PermissionManagerController;
use App\Http\Controllers\Super\RoleManagerController;
use App\Http\Controllers\Super\SiteSettingsController;
use App\Http\Controllers\Super\SystemLogsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin & System Routes (Dynamic Role Based)
|--------------------------------------------------------------------------
|
| Prefix URL dinamis berdasarkan Role user: /{panel_role}/...
| Middleware 'role.prefix' akan memvalidasi apakah user memiliki role tersebut.
| Security: Permission check diterapkan di setiap grup route.
|
*/

// Ganti prefix '{role}' menjadi '{panel_role}' untuk menghindari konflik dengan Route::resource('roles', ...)
// yang secara default menggunakan parameter '{role}'.
Route::prefix('{panel_role}')
    ->middleware(['auth', 'verified', 'role.prefix']) // 'role.prefix' adalah custom middleware kita
    ->name('super.') // Kita pertahankan nama route internal 'super.' agar tidak merusak Controller yang redirect ke sini
    ->group(function () {

        // 1. Dashboard (Permission: view-system-dashboard)
        Route::middleware(['permission:view-system-dashboard','role:super-admin'])->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        });

        // 2. Site Settings (Permission: manage-system-settings)
        Route::middleware(['permission:manage-system-settings','role:super-admin'])->group(function () {
            Route::get('/settings', [SiteSettingsController::class, 'index'])->name('settings.index');
            Route::put('/settings', [SiteSettingsController::class, 'update'])->name('settings.update');
            Route::post('/settings/clear-cache', [SiteSettingsController::class, 'clearCache'])->name('settings.clear-cache');
        });

        // 3. Role Management (Permission: view-roles, manage-roles)
        Route::middleware(['permission:view-roles','role:super-admin'])->group(function () {
            // Parameter resource 'roles' akan menjadi {role}.
            // URL akhir: /{panel_role}/roles/{role} -> Aman, tidak ada duplikasi nama variabel.
            Route::resource('roles', RoleManagerController::class)->except(['create', 'edit', 'show']);
        });

        // 4. Permission Management (Permission: manage-permissions)
        Route::middleware(['permission:manage-permissions','role:super-admin'])->group(function () {
            Route::get('/permissions', [PermissionManagerController::class, 'index'])->name('permissions.index');
            Route::post('/permissions', [PermissionManagerController::class, 'store'])->name('permissions.store');
            Route::post('/permissions/sync', [PermissionManagerController::class, 'syncToRole'])->name('permissions.sync');
        });

        // 5. System Logs (Permission: view-logs)
        Route::middleware(['permission:view-logs','role:super-admin'])->group(function () {
            Route::get('/logs', [SystemLogsController::class, 'index'])->name('logs.index');
            Route::get('/logs/{id}', [SystemLogsController::class, 'show'])->name('logs.show');
            Route::delete('/logs', [SystemLogsController::class, 'destroy'])->name('logs.destroy');
        });

        // 6. API Management (Permission: manage-api-accounts)
        Route::middleware(['permission:manage-api-accounts','role:super-admin'])->group(function () {
            Route::resource('api-accounts', ApiManagementController::class)->except(['create', 'edit', 'show']);
            Route::post('/api-accounts/{account}/rotate', [ApiManagementController::class, 'rotateKeys'])->name('api-accounts.rotate');
        });
    });
