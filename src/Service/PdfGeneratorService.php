<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Response;

class PdfGeneratorService
{
    public function generatePdfFromHtml(string $html): Response
    {
        // Configuration de Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial'); // Police par défaut
        $options->set('isRemoteEnabled', true); // Activer le chargement des ressources distantes (CSS, images)

        $dompdf = new Dompdf($options);

        // Charger le HTML
        $dompdf->loadHtml($html);

        // Définir le format de la page
        $dompdf->setPaper('A4', 'portrait'); 

        // Rendre le PDF
        $dompdf->render();

        // Retourner le PDF sous forme de réponse HTTP
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="export.pdf"',
            ]
        );
    }
}