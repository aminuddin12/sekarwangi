<?php

namespace App\Verification;

use App\Helpers\CodeGenerator;
use App\Helpers\RedisHelper;
use App\Models\User;
use App\Verification\Contracts\Verifier;
use Illuminate\Support\Facades\Log;

class WhatsappVerification implements Verifier
{
    protected $prefix = 'whatsapp_otp:';

    public function send(User $user): void
    {
        // 1. Generate OTP 6 Digit
        $otp = rand(100000, 999999);

        // 2. Simpan ke Redis (Expire 5 menit)
        $key = $this->prefix . $user->id;
        RedisHelper::set($key, $otp, 300); // 300 detik = 5 menit

        // 3. Kirim ke WhatsApp Gateway
        // Disini kita panggil API pihak ketiga (Fonnte/Twilio) via Job Queue
        // Untuk sekarang kita log dulu sebagai simulasi
        $this->sendToGateway($user->phone, $otp);
    }

    public function verify(User $user, string $token): bool
    {
        $key = $this->prefix . $user->id;
        $cachedOtp = RedisHelper::get($key);

        if ($cachedOtp && $cachedOtp == $token) {
            // Valid! Hapus OTP agar tidak bisa dipakai ulang
            RedisHelper::forget($key);

            // Update kolom di user (misal: phone_verified_at) jika ada
            if (method_exists($user, 'markPhoneAsVerified')) {
                $user->markPhoneAsVerified();
            }

            return true;
        }

        return false;
    }

    private function sendToGateway($phone, $otp)
    {
        // TODO: Integrasi Real dengan API WhatsApp
        // Contoh Logika:
        // Http::post('https://api.fonnte.com/send', [...]);

        Log::info("WHATSAPP OTP untuk {$phone}: {$otp}");
    }
}
