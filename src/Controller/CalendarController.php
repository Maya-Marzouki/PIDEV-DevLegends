<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Formation;

class CalendarController extends AbstractController
{
    #[Route('/calendar/events', name: 'calendar_events')]
    public function events(EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer les formations depuis la base de données
        $formations = $entityManager->getRepository(Formation::class)->findAll();

        // Transformer les objets en tableau pour FullCalendar
        $events = [];

        foreach ($formations as $formation) {
            $events[] = [
                'title' => $formation->getTitle(),
                'start' => $formation->getDate()->format('Y-m-d'), // Format date pour FullCalendar
                'location' => $formation->getLocation(),
                'status' => $formation->getStatus(),
                'backgroundColor' => '#007bff', // Personnalisation des couleurs
                'textColor' => '#ffffff'
            ];
        }

        return new JsonResponse($events);
    }
}
