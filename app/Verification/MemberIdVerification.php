<?php

namespace App\Verification;

use App\Helpers\CodeGenerator;
use App\Models\User;
use App\Verification\Contracts\Verifier;
use Illuminate\Support\Str;

class MemberIdVerification implements Verifier
{
    /**
     * Generate ID Member Baru
     * Format: SKW-{YYYY}-{MM}-{RANDOM}
     * Contoh: SKW-2025-01-A7X9
     */
    public function send(User $user): void
    {
        // Cek apakah user sudah punya detail, jika belum buatkan
        $detail = $user->detail()->firstOrCreate(['user_id' => $user->id]);

        // Jika sudah punya ID Number, skip
        if (!empty($detail->id_number)) {
            return;
        }

        // Generate ID Unik
        $year = date('Y');
        $month = date('m');
        $unique = false;
        $newId = '';

        // Loop untuk memastikan unik (Collision Check)
        while (!$unique) {
            $random = strtoupper(Str::random(4));
            $newId = "SKW-{$year}-{$month}-{$random}";

            $exists = \App\Models\UserDetail::where('id_number', $newId)->exists();
            if (!$exists) {
                $unique = true;
            }
        }

        // Simpan ke Database
        $detail->update([
            'id_number' => $newId,
            'join_date' => now(), // Anggap resmi bergabung saat dapat ID
        ]);

        // Opsional: Kirim Email/WA notifikasi ke user bahwa ID sudah terbit
    }

    /**
     * Verifikasi validitas Member ID (Misal saat scan kartu anggota)
     */
    public function verify(User $user, string $token): bool
    {
        // Token disini adalah ID Number yang diinput/discan
        return $user->detail?->id_number === $token;
    }
}
