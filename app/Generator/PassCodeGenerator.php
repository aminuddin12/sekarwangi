<?php

namespace App\Generator;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class PassCodeGenerator
{
    /**
     * Generate PassCode unik untuk login cepat
     * Ini bukan password, tapi kode unik (seperti PIN/Token statis)
     * yang digenerate sistem berdasarkan data user.
     */
    public static function generate(string $username, string $email): array
    {
        // Kombinasi data unik
        $rawString = $username . $email . Str::random(10) . microtime();

        // Buat kode pendek 8-12 karakter yang mudah diketik tapi sulit ditebak
        // Menggunakan CRC32 untuk kependekan + Random String
        $shortHash = strtoupper(dechex(crc32($rawString)));
        $randomPart = strtoupper(Str::random(4));

        $passCode = $shortHash . '-' . $randomPart; // Contoh: 8F2A1B-X9Y2

        // Hash untuk disimpan di database (jangan simpan plain text!)
        $hashedPassCode = Hash::make($passCode);

        return [
            'plain_token' => $passCode, // Tampilkan ke user HANYA SEKALI
            'hashed_token' => $hashedPassCode // Simpan ini di DB
        ];
    }
}
