<?php

namespace App\Controller;

use App\Service\PaypalService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaypalController extends AbstractController
{
    private $paypalService;

    public function __construct(PaypalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }

    #[Route('/paypal/pay', name: 'paypal_pay')]
    public function pay(): Response
    {
        $order = $this->paypalService->createOrder('10.00');
        return $this->redirect($order->links[1]->href); // Redirige vers PayPal
    }

    #[Route('/paypal/success', name: 'paypal_success')]
    public function success(): Response
    {
        // Traitez le paiement réussi
        return $this->render('paypal/success.html.twig');
    }
}