<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class SecurityValidator
{
    /**
     * Validasi Keamanan File Upload
     * Mencegah upload shell script (PHP, PHP5, .exe, dll)
     */
    public static function validateFile(UploadedFile $file, array $allowedMimes = ['image/jpeg', 'image/png', 'application/pdf']): bool
    {
        // 1. Cek MIME Type resmi
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            return false;
        }

        // 2. Cek Ekstensi Ganda (Teknik bypass umum: image.php.jpg)
        $filename = $file->getClientOriginalName();
        if (preg_match('/\.(php|phtml|exe|sh|bat|pl|cgi)\./i', $filename)) {
            return false;
        }

        // 3. Cek Magic Numbers / Header File (Untuk mencegah manipulasi ekstensi)
        // Sederhana: Baca beberapa byte awal
        $path = $file->getRealPath();
        $content = file_get_contents($path, false, null, 0, 20); // Baca header

        // Jika klaim gambar tapi header berisi tag PHP
        if (str_contains($content, '<?php') || str_contains($content, '<?=')) {
            return false;
        }

        return true;
    }

    /**
     * Sanitasi Input String dari Script Berbahaya (XSS)
     * Berguna untuk hasil scan QR yang mungkin berisi script jahat
     */
    public static function sanitizeString(string $input): string
    {
        // Hapus tag HTML dan Script
        $clean = strip_tags($input);

        // Hapus karakter null byte (biasa dipakai untuk exploit binary)
        $clean = str_replace(chr(0), '', $clean);

        // Encode special chars
        return htmlspecialchars($clean, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validasi Format Tanda Tangan (Base64 Image)
     */
    public static function validateSignature(string $base64String): bool
    {
        // Harus diawali dengan header gambar base64
        if (!preg_match('/^data:image\/(png|jpeg|jpg);base64,/', $base64String)) {
            return false;
        }

        // Cek apakah bisa didecode
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64String));
        if ($data === false) {
            return false;
        }

        // Cek apakah benar-benar image (bukan script yang di-base64-kan)
        // Menggunakan getimagesizefromstring (PHP 5.4+)
        try {
            $imgInfo = getimagesizefromstring($data);
            return !empty($imgInfo);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Pengecekan Anomali pada Data Absensi
     * @param string $scannedContent Isi QR yang discan
     * @param string $expectedPrefix Format yang diharapkan (misal: "MBR-")
     */
    public static function validateAttendanceQr(string $scannedContent, string $expectedPrefix): bool
    {
        // 1. Sanitasi dulu
        $cleanContent = self::sanitizeString($scannedContent);

        // 2. Cek Prefix
        if (!Str::startsWith($cleanContent, $expectedPrefix)) {
            return false; // QR tidak valid atau dari sistem lain
        }

        // 3. Cek panjang karakter (mencegah buffer overflow attack sederhana)
        if (strlen($cleanContent) > 255) {
            return false;
        }

        // 4. Cek karakter aneh (hanya boleh alfanumerik dan dash)
        if (!preg_match('/^[a-zA-Z0-9\-]+$/', $cleanContent)) {
            return false;
        }

        return true;
    }
}
