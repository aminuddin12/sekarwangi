<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API V1 Routes Hub
|--------------------------------------------------------------------------
|
| Ini adalah pusat kontrol untuk API Versi 1.
| Semua route dimuat di sini untuk kerapian.
|
*/

// Public / App Routes (Config, Dropdown, Public Data)
require __DIR__ . '/app.php';

// Authentication Routes (Login, Register, Forgot Password)
require __DIR__ . '/auth.php';

// Protected Routes (Butuh Token Sanctum)
Route::middleware(['auth:sanctum'])->group(function () {

    // User Specific Routes (Profile, Notification)
    require __DIR__ . '/user.php';

    // Admin & Management Routes (Role-based)
    require __DIR__ . '/admin.php';

});
