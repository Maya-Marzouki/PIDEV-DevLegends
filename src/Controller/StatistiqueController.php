<?php

namespace App\Controller;

use App\Repository\QuizRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatistiqueController extends AbstractController
{
    #[Route('/admin/statistiques', name: 'admin_statistiques')]
    public function index(QuizRepository $quizRepository): Response
    {
        // Récupérer tous les quiz
        $quizzes = $quizRepository->findAll();

        // Initialiser un tableau pour stocker le nombre de quiz par état mental
        $statsParEtatMental = [];

        // Compter le nombre de quiz pour chaque état mental
        foreach ($quizzes as $quiz) {
            $etatMental = $quiz->getEtatMental();
            if (!isset($statsParEtatMental[$etatMental])) {
                $statsParEtatMental[$etatMental] = 0;
            }
            $statsParEtatMental[$etatMental]++;
        }

        // Préparer les données pour le graphique
        $etatsMentaux = array_keys($statsParEtatMental); // Liste des états mentaux
        $nombreQuiz = array_values($statsParEtatMental); // Nombre de quiz par état mental

        return $this->render('quiz/statistiques.html.twig', [
            'etatsMentaux' => $etatsMentaux,
            'nombreQuiz' => $nombreQuiz,
        ]);
    }
}