<?php

namespace App\Service;

use App\Entity\Question;
use App\Entity\Reponse;
use App\Repository\QuestionRepository;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
// use Symfony\Contracts\HttpClient\HttpClientInterface;


class QuizService
{
    private EntityManagerInterface $entityManager;
    // private HttpClientInterface $httpClient;
    // private string $apiKey;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        // $this->httpClient = $httpClient;
        // $this->apiKey = $apiKey;
    }

    public function getRandomQuestions(int $limit = 10): array
    {
        return $this->entityManager->getRepository(Question::class)->findBy([], [], $limit);
    }

    // public function getRandomQuestions(int $limit = 10): array
    // {
    //     $questions = $this->entityManager->getRepository(Question::class)->findAll();
    //     shuffle($questions);
    //     return array_slice($questions, 0, $limit);
    // }

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
        // Récupérer le champ du formulaire pour cette question
        $fieldName = 'question_' . $question->getId();
        if ($form->has($fieldName)) {
            // Récupérer la réponse sélectionnée par l'utilisateur
            $selectedAnswerId = $form->get($fieldName)->getData();

            // Récupérer l'entité Reponse correspondante depuis la base de données
            $selectedAnswer = $this->entityManager->getRepository(Reponse::class)->find($selectedAnswerId);

            // Ajouter les points de la réponse au score
            if ($selectedAnswer) {
                $score += $selectedAnswer->getScore(); // Supposons que getPoints() retourne les points de la réponse
            }
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
    // public function generateQuiz(): array
    // {
    //     $response = $this->httpClient->request('POST', 'https://api.openai.com/v1/chat/completions', [
    //         'headers' => [
    //             'Authorization' => 'Bearer ' . $this->apiKey,
    //             'Content-Type' => 'application/json',
    //         ],
    //         'json' => [
    //             'model' => 'gpt-4',
    //             'messages' => [
    //                 ['role' => 'system', 'content' => 'Tu es un psychologue générant des tests de santé mentale.'],
    //                 ['role' => 'user', 'content' => 'Génère un test de santé mentale avec 10 questions. Chaque question a 3 réponses possibles : "Jamais" (0 points), "Parfois" (3 points), "Souvent" (5 points).']
    //             ]
    //         ],
    //     ]);

    //     $data = $response->toArray();
    //     return json_decode($data['choices'][0]['message']['content'], true);
    // }

    // public function evaluateQuiz(array $answers): string
    // {
    //     $totalScore = array_sum($answers);

    //     if ($totalScore < 10) {
    //         return "Votre état mental semble bon. Continuez à prendre soin de vous !";
    //     } elseif ($totalScore < 25) {
    //         return "Vous pourriez ressentir du stress ou de l’anxiété. Pensez à consulter un professionnel.";
    //     } else {
    //         return "Votre état mental semble préoccupant. Nous vous recommandons de parler à un spécialiste.";
    //     }
    // }
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