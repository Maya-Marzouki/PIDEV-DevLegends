<?php

namespace App\Service;

use App\Entity\Question;
use App\Entity\Reponse;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;


class QuizService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getRandomQuestions(int $limit = 10): array
    {
        return $this->entityManager->getRepository(Question::class)->findBy([], [], $limit);
    }

    // public function calculateScore(FormInterface $form, array $questions): int
    // {
    //     $score = 0;

    //     foreach ($questions as $question) {
    //         $answer = $form->get('question_' . $question->getId())->getData();
    //         $score += $answer;
    //     }

    //     return $score;
    // }

    public function calculateScore(FormInterface $form, array $questions): int
{
    $score = 0;

    foreach ($questions as $question) {
        // Récupérer la réponse sélectionnée pour cette question
        $reponse = $form->get('question_' . $question->getId())->getData();

        // Vérifier que la réponse est un objet Reponse et a une valeur de score
        if ($reponse instanceof Reponse) {
            $score += $reponse->getScore();
        }
    }

    return $score;
}

    public function interpretScore(int $score): string
    {
        return match (true) {
            $score <= 10 => 'Bonne santé mentale',
            $score <= 20 => 'Légère fatigue émotionnelle',
            $score <= 30 => 'Signes d’anxiété ou de stress',
            $score <= 40 => 'État dépressif modéré',
            default => 'État très préoccupant',
        };
    }
}

    // public function calculateScore(array $answers): int
    // {
    //     $score = 0;
    //     foreach ($answers as $answer) {
    //         $score += is_numeric($answer) ? (int) $answer : 0;
    //     }
    //     return $score;
    // }

    // public function determineMentalState(int $score): string
    // {
    //     return match (true) {
    //         $score <= 10 => 'Bonne santé mentale',
    //         $score <= 20 => 'Légère fatigue émotionnelle',
    //         $score <= 30 => 'Signes d’anxiété ou de stress',
    //         $score <= 40 => 'État dépressif modéré',
    //         default => 'État très préoccupant',
    //     };
    // }