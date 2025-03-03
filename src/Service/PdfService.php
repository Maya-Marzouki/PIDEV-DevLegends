<?php
namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
use Twig\Environment;

class PdfService
{
    private Dompdf $dompdf;
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        // Initialisation de Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $this->dompdf = new Dompdf($options);

        // Injection de Twig
        $this->twig = $twig;
    }

    public function generateInvoice($commande): string
    {
        // Génération du contenu HTML avec un template Twig
        $html = $this->twig->render('commande/facture.html.twig', [
            'commande' => $commande,
        ]);

        // Charger le HTML dans Dompdf
        $this->dompdf->loadHtml($html);

        // Définir la taille du papier
        $this->dompdf->setPaper('A4');

        // Rendre le PDF
        $this->dompdf->render();

        // Retourner le contenu du PDF
        return $this->dompdf->output();
    }
}