<?php

namespace App\Generator;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class TransactionCodeGenerator
{
    /**
     * Membuat Kode Transaksi Unik dan Hash Verifikasi
     * * @param string $prefix Kode awalan (misal: INV, PAY, TRX)
     * @param string $userId ID User pembayar
     * @param float $amount Jumlah transaksi
     * @return array ['code' => '...', 'hash' => '...']
     */
    public static function generate(string $prefix = 'TRX', string $userId = 'GUEST', float $amount = 0): array
    {
        // Format: PREFIX-YYYYMMDD-USERID-RANDOM
        // Contoh: INV-20251220-USR1-A7X9

        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(6));
        $cleanUserId = substr(preg_replace('/[^A-Z0-9]/', '', strtoupper($userId)), 0, 5); // Ambil 5 karakter alfanumerik user

        $code = sprintf("%s-%s-%s-%s", $prefix, $date, $cleanUserId, $random);

        // Membuat Hash Integritas
        // Hash ini disimpan di DB untuk memastikan kode transaksi tidak dimanipulasi
        // Hash menggabungkan Kode + Amount + Secret Key Aplikasi
        $dataToHash = $code . '|' . $amount . '|' . config('app.key');
        $hash = hash_hmac('sha256', $dataToHash, config('app.key'));

        return [
            'code' => $code,
            'hash' => $hash,
            'generated_at' => now(),
        ];
    }

    /**
     * Verifikasi integritas transaksi
     */
    public static function verify(string $code, float $amount, string $storedHash): bool
    {
        $dataToHash = $code . '|' . $amount . '|' . config('app.key');
        $recalculatedHash = hash_hmac('sha256', $dataToHash, config('app.key'));

        return hash_equals($storedHash, $recalculatedHash);
    }
}
