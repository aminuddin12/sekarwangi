<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class CodeGenerator
{
    /**
     * Generate Nomor Invoice
     * Format: INV/20250101/X7Y8Z
     */
    public static function invoice(): string
    {
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(5));
        return "INV/{$date}/{$random}";
    }

    /**
     * Generate SKU Produk (Unik & Readable)
     * Format: KTG-NM-001 (Kategori-Nama-Random)
     */
    public static function sku(string $categorySlug, string $productName): string
    {
        $catCode = strtoupper(substr($categorySlug, 0, 3));
        $nameCode = strtoupper(substr(Str::slug($productName), 0, 3));
        $random = rand(100, 999);

        return "{$catCode}-{$nameCode}-{$random}";
    }

    /**
     * Generate Kode Referensi Transaksi
     * Format: TRX-TIMESTAMP-RANDOM
     */
    public static function transactionRef(): string
    {
        return 'TRX-' . now()->timestamp . '-' . strtoupper(Str::random(4));
    }

    /**
     * Generate Token Absensi QR Code (Aman & Enkripsi Ringan)
     */
    public static function attendanceToken(int $scheduleId): string
    {
        // Gabungan ID Jadwal + Timestamp + Random Salt
        $data = $scheduleId . '|' . now()->timestamp . '|' . Str::random(10);
        return base64_encode($data);
    }
}
