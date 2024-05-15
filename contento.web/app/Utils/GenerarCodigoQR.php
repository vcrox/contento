<?php

namespace App\Utils;

use CodeItNow\BarcodeBundle\Utils\QrCode;

class GenerarCodigoQR
{
    public function generar($text)
    {
        $qrCode = new QrCode();
        $qrCode
            ->setText($text)
            ->setSize(300)
            ->setPadding(10)
            ->setErrorCorrection('high')
            ->setImageType(QrCode::IMAGE_TYPE_PNG);

        return $qrCode->generate();
    }
}
