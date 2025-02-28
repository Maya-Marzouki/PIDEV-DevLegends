<?php

namespace App\Service;

use Mollie\Api\MollieApiClient;

class MollieService
{
    private MollieApiClient $mollie;

    public function __construct(string $mollieApiKey)
    {
        $this->mollie = new MollieApiClient();
        $this->mollie->setApiKey($mollieApiKey);
    }

    public function createPayment($totalCommande, $description, $redirectUrl)
    {
        if ($totalCommande <= 0) {
            throw new \Exception('Le montant de la commande doit être supérieur à zéro.');
        }
    
        // Continuez avec l'appel Mollie
        $payment = $this->mollie->payments->create([
            'amount' => [
                'currency' => 'EUR',
                'value' => number_format($totalCommande, 2, '.', ''),
            ],
            'description' => $description,
            'redirectUrl' => $redirectUrl,
        ]);
    
        return $payment->getCheckoutUrl();
    }
    
}
