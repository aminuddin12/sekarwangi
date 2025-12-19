<?php

use Illuminate\Support\Facades\Route;

// Semua route di sini dilindungi permission
Route::prefix('admin')->group(function () {

    // User Management
    Route::middleware('permission:view-users')->group(function() {
        // Route::apiResource('users', UserController::class);
    });

    // Finance Management
    Route::middleware('permission:view-finance')->prefix('finance')->group(function() {
        // Route::get('summary', [FinanceController::class, 'summary']);
    });

    // System Logs
    Route::middleware('role:super-admin')->get('logs', function() {
        return \App\Handlers\ResponseHandler::success(\App\Models\SystemActivityLog::latest()->take(10)->get());
    });
});
