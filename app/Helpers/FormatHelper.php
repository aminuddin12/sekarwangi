<?php

namespace App\Helpers;

use Carbon\Carbon;
use NumberFormatter;

class FormatHelper
{
    /**
     * Format Angka ke Mata Uang (IDR/USD)
     */
    public static function currency(float|int $amount, string $currency = 'IDR'): string
    {
        $locale = $currency === 'IDR' ? 'id_ID' : 'en_US';
        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

        // Hilangkan desimal ,00 jika IDR agar lebih bersih
        if ($currency === 'IDR') {
            $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
        }

        return $formatter->formatCurrency($amount, $currency);
    }

    /**
     * Format Tanggal Indonesia Lengkap
     * Contoh: Senin, 1 Januari 2025
     */
    public static function dateIndo($date, bool $dayName = true): string
    {
        if (!$date) return '-';

        $carbon = Carbon::parse($date)->locale('id');
        $format = $dayName ? 'l, d F Y' : 'd F Y';

        return $carbon->translatedFormat($format);
    }

    /**
     * Format Ukuran File (Bytes ke KB, MB, GB)
     */
    public static function fileSize(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Masking Email/Phone untuk Privasi (e.g., j***@gmail.com)
     */
    public static function mask(string $value, string $type = 'email'): string
    {
        if ($type === 'email') {
            $parts = explode('@', $value);
            if (count($parts) < 2) return $value;

            $name = $parts[0];
            $domain = $parts[1];

            $maskedName = substr($name, 0, 1) . str_repeat('*', max(strlen($name) - 2, 3)) . substr($name, -1);
            return $maskedName . '@' . $domain;
        }

        // Phone Masking (0812****8888)
        return substr($value, 0, 4) . str_repeat('*', max(strlen($value) - 8, 4)) . substr($value, -4);
    }
}
