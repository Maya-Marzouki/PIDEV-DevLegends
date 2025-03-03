<?php
namespace App\Service;

use Mollie\Api\MollieApiClient;
use Psr\Log\LoggerInterface;

class MollieService
{
    private MollieApiClient $mollie;
    private LoggerInterface $logger;
    private ?string $paymentId = null;

    public function __construct(string $mollieApiKey, LoggerInterface $logger)
    {
        $this->mollie = new MollieApiClient();
        $this->mollie->setApiKey($mollieApiKey);  // API key est bien définie ici
        $this->logger = $logger;  // Initialisation du logger
    }

    public function createPayment(float $amount, string $description, string $redirectUrl): string
    {
        try {
            // Utilisation de l'objet $this->mollie (pas besoin de recréer une instance)
            $payment = $this->mollie->payments->create([
                'amount' => [
                    'currency' => 'EUR',  // Change this to your currency
                    'value' => number_format($amount, 2, '.', ''),
                ],
                'description' => $description,
                'redirectUrl' => $redirectUrl,
            ]);

            $this->paymentId = $payment->id;  // Stockage de l'ID du paiement
            return $payment->getCheckoutUrl();  // Retourne l'URL de paiement
        } catch (\Mollie\Api\Exceptions\ApiException $e) {
            $this->logger->error('Erreur lors de la création du paiement Mollie : ' . $e->getMessage());
            throw new \RuntimeException('Impossible de créer le paiement Mollie.');
        }
    }

    public function getPaymentId(): ?string
    {
        return $this->paymentId;  // Récupérer l'ID du paiement
    }
}
