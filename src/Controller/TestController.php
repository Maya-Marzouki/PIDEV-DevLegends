<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class TestController
{
    #[Route('/test-api-key', name: 'test_api_key')]
    public function test(): Response
    {
        $apiKey = $_ENV['UNSPLASH_ACCESS_KEY'] ?? 'Clé non trouvée';
        return new JsonResponse(['Unsplash API Key' => $apiKey]);
    }
}
