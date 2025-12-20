<?php

use Illuminate\Support\Facades\Route;
// Nanti arahkan ke API Controller khusus, bukan Fortify default jika ingin JSON response murni
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\VerificationController;

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

    Route::middleware('auth:sanctum')->group(function() {

        // Email
        Route::post('email/verification-notification', [VerificationController::class, 'sendEmailVerification'])
            ->middleware(['throttle:6,1'])
            ->name('verification.send');

        Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verifyEmail'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        // WhatsApp
        Route::post('whatsapp/send-otp', [VerificationController::class, 'sendWhatsappOtp'])
            ->middleware('throttle:3,1'); // Max 3x kirim per menit

        Route::post('whatsapp/verify-otp', [VerificationController::class, 'verifyWhatsappOtp']);
    });
});
