<?php

use Illuminate\Support\Facades\Route;

Route::prefix('app')->group(function () {

    // Check Health / Ping
    Route::get('ping', function() {
        return response()->json(['status' => 'ok', 'timestamp' => now()]);
    });

    // Public Settings (Site Name, Logo)
    // Route::get('settings', [SettingController::class, 'publicConfig']);

    // Master Data (Dropdown Provinisi, Kota, dll)
    // Route::get('regions', [RegionController::class, 'index']);
});
