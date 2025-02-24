<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfGeneratorService
{
    public function generatePdfFromHtml(string $html): string
    {
        // Configuration de Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial'); // Police par défaut
        $options->set('isRemoteEnabled', true); // Activer le chargement des ressources distantes (CSS, images)

        $dompdf = new Dompdf($options);

        // Charger le HTML
        $dompdf->loadHtml($html);

        // Rendre le PDF
        $dompdf->setPaper('A4', 'portrait'); // Format A4, orientation portrait
        $dompdf->render();

        // Retourner le contenu du PDF
        return $dompdf->output();
    }
}