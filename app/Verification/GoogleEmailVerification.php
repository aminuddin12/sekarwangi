<?php

namespace App\Verification;

use App\Helpers\SystemSetting;
use App\Models\User;
use App\Verification\Contracts\Verifier;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;

class GoogleEmailVerification implements Verifier
{
    public function send(User $user): void
    {
        // Cek config dari database (Super Admin Settings)
        // Jika tidak ada di DB, pakai default config Laravel (60 menit)
        $expiryMinutes = SystemSetting::get('auth_email_verification_expiry', 60);

        // Trigger notification bawaan Laravel yang berisi Signed URL
        // Pastikan User model implement MustVerifyEmail
        $user->sendEmailVerificationNotification();
    }

    public function verify(User $user, string $token = null): bool
    {
        if ($user->hasVerifiedEmail()) {
            return true;
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
            return true;
        }

        return false;
    }

    /**
     * Generate Signed URL manual (jika ingin kirim via API JSON response)
     */
    public function generateUrl(User $user): string
    {
        $expiry = SystemSetting::get('auth_email_verification_expiry', 60);

        return URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes($expiry),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );
    }
}
