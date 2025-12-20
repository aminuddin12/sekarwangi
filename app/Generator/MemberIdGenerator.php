<?php

namespace App\Generator;

use Illuminate\Support\Str;
use App\Generator\QrCodeGenerator;

class MemberIdGenerator
{
    /**
     * Generate Member ID & QR Code
     * Format: MBR-YYYY-RANDOM
     * * @return array ['id_number' => '...', 'qr_image' => '...']
     */
    public static function generate(): array
    {
        $year = date('Y');
        $random = strtoupper(Str::random(8));

        // ID Unik
        $idNumber = "MBR-{$year}-{$random}";

        // Generate QR untuk ID tersebut
        // QR ini nanti bisa discan untuk absensi atau cek profil member
        $qrImage = QrCodeGenerator::generate($idNumber, $idNumber);

        return [
            'id_number' => $idNumber,
            'qr_image' => $qrImage
        ];
    }
}
