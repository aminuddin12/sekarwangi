<?php

use Illuminate\Support\Facades\Route;
// Nanti arahkan ke API Controller khusus, bukan Fortify default jika ingin JSON response murni
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;

Route::prefix('auth')->group(function () {
    // Login & Register
    // Note: Pastikan Controller API ini dibuat nanti
    // Route::post('login', [LoginController::class, 'login']);
    // Route::post('register', [RegisterController::class, 'register']);

    // Password Reset
    // Route::post('forgot-password', [PasswordResetController::class, 'sendResetLink']);

    // Google Auth (API)
    // Route::post('google', [SocialAuthController::class, 'googleLogin']);

    Route::get('test', function() {
        return response()->json(['message' => 'Auth endpoint reached']);
    });
});
