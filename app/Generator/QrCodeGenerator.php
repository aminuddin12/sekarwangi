<?php

namespace App\Generator;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeGenerator
{
    /**
     * Generate QR Code dalam format Base64 Image
     * * @param string $data Data yang akan dikodekan (URL/Text/ID)
     * @param string|null $label Label teks di bawah QR (Opsional)
     * @return string Data URI (data:image/png;base64,...)
     */
    public static function generate(string $data, ?string $label = null): string
    {
        $builder = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($data)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High) // Level koreksi kesalahan tinggi
            ->size(300)
            ->margin(10)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->validateResult(false);

        if ($label) {
            $builder->labelText($label)
                ->labelFont(new NotoSans(20))
                ->labelAlignment(LabelAlignment::Center);
        }

        $result = $builder->build();

        return $result->getDataUri();
    }

    /**
     * Generate QR Code dan simpan ke file fisik
     */
    public static function saveToFile(string $data, string $path, ?string $label = null): bool
    {
        try {
            $base64 = self::generate($data, $label);
            $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64));
            return file_put_contents($path, $image) !== false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
