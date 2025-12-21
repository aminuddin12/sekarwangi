<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\VerificationController;
// use App\Http\Controllers\Api\Auth\PasswordResetController; // Pastikan controller ini ada jika dipakai
// use App\Http\Controllers\Api\Auth\SocialAuthController; // Pastikan controller ini ada jika dipakai

Route::prefix('auth')->group(function () {
    // --- PUBLIC ROUTES ---

    // Login & Register (Uncomment jika controller sudah siap)
    // Route::post('login', [LoginController::class, 'login'])->name('auth.login');
    // Route::post('register', [RegisterController::class, 'register'])->name('auth.register');

    // Password Reset (Uncomment jika controller sudah siap)
    // Route::post('forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('auth.password.email');
    // Route::post('reset-password', [PasswordResetController::class, 'reset'])->name('auth.password.update');

    // Google Auth (API)
    // Route::post('google', [SocialAuthController::class, 'googleLogin'])->name('auth.social.google');

    Route::get('test', function() {
        return response()->json(['message' => 'Auth endpoint reached']);
    })->name('auth.test');

    // --- PROTECTED ROUTES (Sanctum) ---
    Route::middleware('auth:sanctum')->group(function() {

        // 1. Email Verification
        // Menggunakan nama route yang sangat spesifik 'verification.email.send'
        Route::post('email/verification-notification', [VerificationController::class, 'sendEmailVerification'])
            ->middleware(['throttle:6,1'])
            ->name('verification.email.send');

        // Menggunakan nama route 'verification.email.verify'
        // Parameter {id} dan {hash} diperlukan untuk signed url
        Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verifyEmail'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.email.verify');

        // 2. WhatsApp OTP
        Route::post('whatsapp/send-otp', [VerificationController::class, 'sendWhatsappOtp'])
            ->middleware('throttle:3,1') // Max 3x kirim per menit
            ->name('verification.whatsapp.send');

        Route::post('whatsapp/verify-otp', [VerificationController::class, 'verifyWhatsappOtp'])
            ->name('verification.whatsapp.verify');
    });
});
