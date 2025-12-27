<?php

use App\Http\Controllers\Super\ApiManagementController;
use App\Http\Controllers\Super\DashboardController;
use App\Http\Controllers\Super\PermissionManagerController;
use App\Http\Controllers\Super\RoleManagerController;
use App\Http\Controllers\Super\SiteSettingsController;
use App\Http\Controllers\Super\SystemLogsController;
use App\Http\Controllers\System\UrlManagementController;
use Illuminate\Support\Facades\Route;

Route::prefix('{panel_role}')
    ->middleware(['auth', 'verified', 'role.prefix'])
    ->name('super.')
    ->group(function () {

        Route::middleware(['permission:view-system-dashboard'])->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        });

        Route::middleware(['permission:manage-system-settings'])->group(function () {
            Route::get('/settings', [SiteSettingsController::class, 'index'])->name('settings.index');
            Route::put('/settings', [SiteSettingsController::class, 'update'])->name('settings.update');
            Route::post('/settings/clear-cache', [SiteSettingsController::class, 'clearCache'])->name('settings.clear-cache');
        });

        Route::middleware(['role:super-admin'])->group(function () {
            Route::get('/urls', [UrlManagementController::class, 'index'])->name('urls.index');
            Route::post('/urls', [UrlManagementController::class, 'store'])->name('urls.store');
            Route::post('/urls/reorder', [UrlManagementController::class, 'reorder'])->name('urls.reorder');
            Route::put('/urls/{id}', [UrlManagementController::class, 'update'])->name('urls.update');
            Route::delete('/urls/{id}', [UrlManagementController::class, 'destroy'])->name('urls.destroy');
            Route::post('/urls/groups', [UrlManagementController::class, 'storeGroup'])->name('urls.groups.store');
        });

        Route::middleware(['permission:view-roles'])->group(function () {
            Route::resource('roles', RoleManagerController::class)->except(['create', 'edit', 'show']);
        });

        Route::middleware(['permission:manage-permissions'])->group(function () {
            Route::get('/permissions', [PermissionManagerController::class, 'index'])->name('permissions.index');
            Route::post('/permissions', [PermissionManagerController::class, 'store'])->name('permissions.store');
            Route::post('/permissions/sync', [PermissionManagerController::class, 'syncToRole'])->name('permissions.sync');
        });

        Route::middleware(['permission:view-logs'])->group(function () {
            Route::get('/logs', [SystemLogsController::class, 'index'])->name('logs.index');
            Route::get('/logs/{id}', [SystemLogsController::class, 'show'])->name('logs.show');
            Route::delete('/logs', [SystemLogsController::class, 'destroy'])->name('logs.destroy');
        });

        Route::middleware(['permission:manage-api-accounts'])->group(function () {
            Route::resource('api-accounts', ApiManagementController::class)->except(['create', 'edit', 'show']);
            Route::post('/api-accounts/{account}/rotate', [ApiManagementController::class, 'rotateKeys'])->name('api-accounts.rotate');
        });
    });
