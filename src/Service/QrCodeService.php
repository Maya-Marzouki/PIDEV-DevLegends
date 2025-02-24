<?php

namespace App\Service;

class QrCodeService
{
    /**
     * Génère un QR code en utilisant Google Charts API.
     *
     * @param array $data Les données à encoder dans le QR code.
     * @param string $size La taille du QR code (format: "largeurxhauteur").
     * @return string L'URL de l'image du QR code.
     */
    public function generateQrCode(array $data, string $size = '300x300'): string
    {
        // Convertir les données en JSON
        $jsonData = json_encode($data);
    
        // Construire l'URL de l'API QR Code Monkey
        return "https://api.qrcode-monkey.com/qr/custom?data=" . urlencode($jsonData) . "&size=$size";
    }
}