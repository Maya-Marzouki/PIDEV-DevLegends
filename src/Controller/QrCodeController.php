<?php

namespace App\Controller;

use App\Service\QrCodeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QrCodeController extends AbstractController
{
    private $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * @Route("/qr-code", name="qr_code")
     */
    public function generateQrCode(): Response
    {
        // Données à encoder dans le QR code
        $data = 'https://www.example.com';

        // Générer l'URL du QR code
        $qrCodeUrl = $this->qrCodeService->generateQrCode($data);

        // Passer l'URL à la vue
        return $this->render('qr_code/index.html.twig', [
            'qrCodeUrl' => $qrCodeUrl,
        ]);
    }
}