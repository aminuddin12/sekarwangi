<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\ConfirmablePasswordController;
use Laravel\Fortify\Http\Controllers\ConfirmedPasswordStatusController;
use Laravel\Fortify\Http\Controllers\ConfirmedTwoFactorAuthenticationController;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;
use Laravel\Fortify\Http\Controllers\EmailVerificationPromptController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\RecoveryCodeController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticationController;
use Laravel\Fortify\Http\Controllers\TwoFactorQrCodeController;
use Laravel\Fortify\Http\Controllers\TwoFactorSecretKeyController;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;

Route::middleware('guest')->group(function () {

    // --- 1. CUSTOM LOGIN (Support WA/Email/Username) ---
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);

    // --- 2. CUSTOM REGISTER (Support Member ID Gen) ---
    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);

    // --- 3. SOCIAL LOGIN ---
    Route::get('auth/{provider}', [SocialAuthController::class, 'redirect'])->name('auth.social.redirect');
    Route::get('auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('auth.social.callback');

    // --- 4. PASSWORD RESET (Standard Fortify Logic) ---
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');

    // --- 5. TWO FACTOR CHALLENGE ---
    Route::get('two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'create'])->name('two-factor.login');
    Route::post('two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {

    // --- LOGOUT ---
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    // --- EMAIL VERIFICATION ---
    Route::get('email/verify', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // --- PASSWORD CONFIRMATION (Untuk aksi sensitif) ---
    Route::get('user/confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('user/confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::get('user/confirmed-password-status', [ConfirmedPasswordStatusController::class, 'show'])->name('password.confirmation');

    // --- TWO FACTOR AUTHENTICATION MANAGEMENT ---
    // Route ini digunakan di halaman Settings Profile
    Route::post('user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'store'])->name('two-factor.enable');
    Route::delete('user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'destroy'])->name('two-factor.disable');
    Route::get('user/two-factor-qr-code', [TwoFactorQrCodeController::class, 'show'])->name('two-factor.qr-code');
    Route::get('user/two-factor-secret-key', [TwoFactorSecretKeyController::class, 'show'])->name('two-factor.secret-key');
    Route::get('user/two-factor-recovery-codes', [RecoveryCodeController::class, 'index'])->name('two-factor.recovery-codes');
    Route::post('user/two-factor-recovery-codes', [RecoveryCodeController::class, 'store']);
    Route::post('user/confirmed-two-factor-authentication', [ConfirmedTwoFactorAuthenticationController::class, 'store'])->name('two-factor.confirm');
});
