<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Form\QuizUserResponseType;
use App\Repository\QuestionRepository;
use App\Repository\QuizRepository;
use App\Service\QuizService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/quiz')]
class QuizController extends AbstractController
{
    private QuizService $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    #[Route('/user/start', name: 'quiz_start', methods: ['GET', 'POST'])]
    public function startQuiz(Request $request): Response
    {
        // Récupérer les questions aléatoires via le service
        $questions = $this->quizService->getRandomQuestions();

        if (count($questions) < 10) {
            $this->addFlash('warning', 'Il n’y a pas assez de questions disponibles.');
            return $this->redirectToRoute('homepage');
        }

        // Créer le formulaire avec les questions récupérées
        $form = $this->createForm(QuizUserResponseType::class, null, [
            'questions' => $questions,
        ]);

        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Calculer le score avec le service
            $score = $this->quizService->calculateScore($form, $questions);

            // Déterminer l'état mental en fonction du score
            $etatMental = $this->quizService->interpretScore($score);

            return $this->render('quiz/result.html.twig', [
                'etatMental' => $etatMental,
                'score' => $score,
            ]);
        }

        return $this->render('quiz/quizForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/list', name: 'quiz_list', methods: ['GET', 'POST'])]
    public function listQuizzes(QuizRepository $quizRepository): Response
    {
        // Récupérer tous les quiz
        $quizzes = $quizRepository->findAll();

        // Passer les quiz à la vue
        return $this->render('quiz/list.html.twig', [
            'quizzes' => $quizzes,
        ]);
    }

}