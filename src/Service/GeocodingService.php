<?php
namespace App\Service;

use \App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeocodingService
{
    private $httpClient;
    private $entityManager;

    public function __construct(HttpClientInterface $httpClient, EntityManagerInterface $entityManager)
    {
        $this->httpClient = $httpClient;
        $this->entityManager = $entityManager;
    }

    public function getCoordinates(User $user): ?array
{
    // Si les coordonnées sont déjà en base de données, les retourner
    if ($user->getLatitude() && $user->getLongitude()) {
        return [
            'latitude' => $user->getLatitude(),
            'longitude' => $user->getLongitude(),
        ];
    }

    // Sinon, faire une requête à Nominatim
    $coordinates = $this->fetchCoordinates($user->getAddress());

    if ($coordinates) {
        // Enregistrer les coordonnées dans la base de données
        $user->setLatitude($coordinates['latitude']);
        $user->setLongitude($coordinates['longitude']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    return $coordinates;
}

    private function fetchCoordinates(string $address): ?array
    {
        $encodedAddress = urlencode($address);
        $url = "https://nominatim.openstreetmap.org/search?format=json&q={$encodedAddress}";

        $response = $this->httpClient->request('GET', $url, [
            'headers' => [
                'User-Agent' => 'YourAppName/1.0 (contact@yourapp.com)',
            ],
        ]);

        $data = $response->toArray();

        if (!empty($data)) {
            return [
                'latitude' => (float) $data[0]['lat'],
                'longitude' => (float) $data[0]['lon'],
            ];
        }

        return null;
    }
}