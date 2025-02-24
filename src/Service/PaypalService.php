<?php

namespace App\Service;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\LiveEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PaypalService
{
    private $client;

    public function __construct(string $paypalClientId, string $paypalSecret, string $paypalMode)
    {
        $environment = $paypalMode === 'sandbox'
            ? new SandboxEnvironment($paypalClientId, $paypalSecret)
            : new LiveEnvironment($paypalClientId, $paypalSecret);

        $this->client = new PayPalHttpClient($environment);
    }

    public function createOrder($amount)
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "amount" => [
                    "currency_code" => "EUR",
                    "value" => $amount
                ]
            ]]
        ];

        $response = $this->client->execute($request);
        return $response->result;
    }

    public function captureOrder($orderId)
    {
        $request = new OrdersCaptureRequest($orderId);
        $response = $this->client->execute($request);
        return $response->result;
    }
}