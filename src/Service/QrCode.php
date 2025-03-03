<?php

namespace App\Service;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RequestStack;

class QrCodeService
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function generateQrCode(string $data): string
    {
        // Création du QR Code avec le constructeur (version 6.x)
        $qrCode = new QrCode($data);

        // Générer le QR Code en image
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Définir le chemin où sauvegarder l'image
        $fileName = 'qr_code.png';
        $filePath = 'public/qr_codes/' . $fileName;

        // Vérifier et créer le dossier si nécessaire
        $filesystem = new Filesystem();
        if (!$filesystem->exists('public/qr_codes')) {
            $filesystem->mkdir('public/qr_codes', 0777);
        }

        // Sauvegarde de l'image
        file_put_contents($filePath, $result->getString());

        // Générer l'URL complète du QR Code
        $request = $this->requestStack->getCurrentRequest();
        return $request->getSchemeAndHttpHost() . 'public/qr_codes/' . $fileName;
    }
}



// namespace App\Service;

// class QrCodeService
// {
//     public function generateQrCode(string $data, string $url, string $size = '300x300'): string
//     {
//                 // Convertir les données en format URL
//                 $encodedData = urlencode($data);

//                 // Construire l'URL avec l'API Google Charts
//                 return "https://chart.googleapis.com/chart?chs={$size}&cht=qr&chl={$encodedData}&choe=UTF-8";
//         // // Construire l'URL du QR code qui redirige vers une page Symfony
//         // return "https://chart.googleapis.com/chart?chs=$size&cht=qr&chl=" . \urlencode($url) . "&choe=UTF-8";
//     }
// }


// namespace App\Service;

// class QrCodeService
// {
//     /**
//      * Génère un QR code en utilisant Google Charts API.
//      *
//      * @param array $data Les données à encoder dans le QR code.
//      * @param string $size La taille du QR code (format: "largeurxhauteur").
//      * @return string L'URL de l'image du QR code.
//      */
//     public function generateQrCode(array $data, string $size = '300x300'): string
//     {
//         // Convertir les données en JSON
//         $jsonData = \json_encode($data);

//         // Construire l'URL de l'API QR Code Monkey
//         return "https://api.qrcode-monkey.com/qr/custom?data=" . \urlencode($jsonData) . "&size=$size";
//     }
// }