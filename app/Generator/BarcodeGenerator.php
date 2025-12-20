<?php

namespace App\Generator;

use Picqer\Barcode\BarcodeGeneratorPNG;

class BarcodeGenerator
{
    /**
     * Generate Barcode (Code 128) dalam format Base64
     * Code 128 mendukung angka dan huruf.
     */
    public static function generate(string $code): string
    {
        $generator = new BarcodeGeneratorPNG();

        // Menghasilkan gambar barcode raw
        $barcodeData = $generator->getBarcode($code, $generator::TYPE_CODE_128, 2, 50); // width factor 2, height 50

        // Convert ke Base64 agar mudah ditampilkan di <img src="...">
        $base64 = base64_encode($barcodeData);

        return 'data:image/png;base64,' . $base64;
    }
}
