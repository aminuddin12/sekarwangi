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
| Super Admin Routes
|--------------------------------------------------------------------------
|
| Semua route di sini diproteksi middleware 'role:super-admin'.
| Prefix URL: /super
| Name Prefix: super.
|
*/

Route::middleware(['auth', 'verified', 'role:super-admin'])->prefix('super')->name('super.')->group(function () {

    // 1. Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Site Settings (Pengaturan Global)
    Route::get('/settings', [SiteSettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SiteSettingsController::class, 'update'])->name('settings.update');

    // 3. Role & Permission Management
    Route::resource('roles', RoleManagerController::class)->except(['create', 'edit', 'show']);
    Route::get('permissions', [PermissionManagerController::class, 'index'])->name('permissions.index');
    Route::post('permissions', [PermissionManagerController::class, 'store'])->name('permissions.store');

    // 4. System Logs (Audit Trail)
    Route::get('/logs', [SystemLogsController::class, 'index'])->name('logs.index');
    Route::get('/logs/{id}', [SystemLogsController::class, 'show'])->name('logs.show');

    // 5. API Management (Integrasi)
    Route::resource('api-accounts', ApiManagementController::class)->except(['create', 'edit', 'show']);
    Route::post('api-accounts/{account}/rotate', [ApiManagementController::class, 'rotateKeys'])->name('api-accounts.rotate');

});
