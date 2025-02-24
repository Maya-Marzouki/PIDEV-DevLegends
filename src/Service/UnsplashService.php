<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class UnsplashService
{
    private HttpClientInterface $httpClient;
    private string $unsplashAccessKey;

    public function __construct(HttpClientInterface $httpClient, string $unsplashAccessKey)
    {
        $this->httpClient = $httpClient;
        $this->unsplashAccessKey = $unsplashAccessKey;
    }

    public function getImageByKeyword(string $keyword): ?string
    {
        $response = $this->httpClient->request('GET', 'https://api.unsplash.com/photos/random', [
            'query' => [
                'query' => $keyword,
                'client_id' => $this->unsplashAccessKey,
            ],
        ]);

        $data = $response->toArray();
        return $data['urls']['regular'] ?? null; // Retourne l'URL de l'image
    }
}

// class UnsplashService
// {
//     private HttpClientInterface $client;
//     private string $accessKey;

//     public function __construct(HttpClientInterface $client, string $unsplashAccessKey)
//     {
//         $this->client = $client;
//         $this->accessKey = $unsplashAccessKey;
//     }

//     public function getImageByKeyword(string $keyword): ?string
//     {
//         $response = $this->client->request('GET', 'https://api.unsplash.com/photos/random', [
//             'query' => [
//                 'query' => $keyword,
//                 'client_id' => $this->accessKey,
//             ],
//         ]);
    
//         // Vérifie si la requête a réussi (code HTTP 200)
//         if ($response->getStatusCode() !== 200) {
//             return null; // Retourne null si la requête a échoué
//         }
    
//         $data = $response->toArray();
    
//         return $data[0]['urls']['regular'] ?? null;// Retourne l'URL de l'image
//     }
    
// }
