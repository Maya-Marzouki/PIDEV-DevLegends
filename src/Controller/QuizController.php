<?php

namespace App\Controller;

use App\Service\UnsplashService;
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
use Knp\Component\Pager\PaginatorInterface;

#[Route('/quiz')]
class QuizController extends AbstractController
{
    private QuizService $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    // #[Route('/user/start', name: 'quiz_start', methods: ['GET', 'POST'])]
    // public function startQuiz(Request $request): Response
    // {
    //     // Récupérer les questions aléatoires via le service
    //     $questions = $this->quizService->getRandomQuestions();

    //     if (count($questions) < 10) {
    //         $this->addFlash('warning', 'Il n’y a pas assez de questions disponibles.');
    //         return $this->redirectToRoute('homepage');
    //     }

    //     // Créer le formulaire avec les questions récupérées
    //     $form = $this->createForm(QuizUserResponseType::class, null, [
    //         'questions' => $questions,
    //     ]);

    //     $form->handleRequest($request);

    //     // Si le formulaire est soumis et valide
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // Calculer le score avec le service
    //         $score = $this->quizService->calculateScore($form, $questions);

    //         // Déterminer l'état mental en fonction du score
    //         $etatMental = $this->quizService->interpretScore($score);

    //         return $this->render('quiz/result.html.twig', [
    //             'etatMental' => $etatMental,
    //             'score' => $score,
    //             // 'imageUrl' => $imageUrl,
    //         ]);
    //     }

    //     return $this->render('quiz/quizForm.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

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

            // Rediriger vers la route `quiz_result` avec les paramètres nécessaires
            return $this->redirectToRoute('quiz_result', [
                'score' => $score,
                'etatMental' => $etatMental,
            ]);
        }

        return $this->render('quiz/quizForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // #[Route('/quiz/result', name: 'quiz_result')]
    // public function result(Request $request,UnsplashService $unsplashService): Response
    // {
    //     // Récupérer les paramètres de la requête
    //     $score = $request->query->get('score');
    //     $etatMental = $request->query->get('etatMental');

    //     // Vérifier si les paramètres sont présents
    //     if (!$score || !$etatMental) {
    //         throw $this->createNotFoundException('Paramètres manquants pour afficher le résultat.');
    //     }
        
    //     $score = 24; // Exemple de score (remplace-le avec la vraie valeur)
    
    //     // Déterminer l'état mental
    //     $etatMental = match (true) {
    //         $score <= 10 => 'Bonne santé mentale',
    //         $score <= 20 => 'Légère fatigue émotionnelle',
    //         $score <= 30 => 'Signes d’anxiété ou de stress',
    //         $score <= 40 => 'État dépressif modéré',
    //         default => 'État très préoccupant',
    //     };
    
    //     // Associer un mot-clé pour Unsplash
    //     $keywords = [
    //         'Bonne santé mentale' => 'happy mind',
    //         'Légère fatigue émotionnelle' => 'calm nature',
    //         'Signes d’anxiété ou de stress' => 'stress relief',
    //         'État dépressif modéré' => 'dark mood',
    //         'État très préoccupant' => 'mental health struggle'
    //     ];
    
    //     $keyword = $keywords[$etatMental] ?? 'mental health';
    //     $imageUrl = $unsplashService->getImageByKeyword($keyword);
    
    //     // Debugging pour voir si l'URL de l'image est récupérée correctement
    //     dd($imageUrl); // Cela arrêtera l'exécution et affichera la valeur
    
    //     return $this->render('quiz/result.html.twig', [
    //         'score' => $score,
    //         'etatMental' => $etatMental,
    //         'imageUrl' => $imageUrl
    //     ]);
    // }
    
    #[Route('/quiz/result', name: 'quiz_result')]
public function result(Request $request, UnsplashService $unsplashService, EntityManagerInterface $entityManager): Response
{
    // Récupérer les paramètres de la requête
    $score = $request->query->get('score');
    $etatMental = $request->query->get('etatMental');

    // Vérifier si les paramètres sont présents
    if (!$score || !$etatMental) {
        throw $this->createNotFoundException('Paramètres manquants pour afficher le résultat.');
    }

    // Associer un mot-clé pour Unsplash
    $keywords = [
        'Bonne santé mentale' => 'happy mind',
        'Légère fatigue émotionnelle' => 'calm nature',
        'Signes d’anxiété ou de stress' => 'stress relief',
        'État dépressif modéré' => 'dark mood',
        'État très préoccupant' => 'mental health struggle'
    ];

    $keyword = $keywords[$etatMental] ?? 'mental health';
    $imageUrl = $unsplashService->getImageByKeyword($keyword);

    // Créer une nouvelle instance de Quiz
    $quiz = new Quiz();
    $quiz->setScore($score);
    $quiz->setEtatMental($etatMental);
    // $quiz->setImageUrl($imageUrl); // Décommentez si vous souhaitez enregistrer l'URL de l'image

    // Enregistrer le quiz dans la base de données
    $entityManager->persist($quiz);
    $entityManager->flush();

    // Debugging pour voir si l'URL de l'image est récupérée correctement
    // dd($imageUrl); // Décommentez pour vérifier

    return $this->render('quiz/result.html.twig', [
        'score' => $score,
        'etatMental' => $etatMental,
        'imageUrl' => $imageUrl
    ]);
}

//     #[Route('/quiz/result', name: 'quiz_result')]
// public function result(Request $request, UnsplashService $unsplashService, EntityManagerInterface $entityManager): Response
// {
//     // Récupérer les paramètres de la requête
//     $score = $request->query->get('score');
//     $etatMental = $request->query->get('etatMental');

//     // Vérifier si les paramètres sont présents
//     if (!$score || !$etatMental) {
//         throw $this->createNotFoundException('Paramètres manquants pour afficher le résultat.');
//     }
        
//     $score = 24; // Exemple de score (remplace-le avec la vraie valeur)
    
//         // Déterminer l'état mental
//         $etatMental = match (true) {
//         $score <= 10 => 'Bonne santé mentale',
//         $score <= 20 => 'Légère fatigue émotionnelle',
//         $score <= 30 => 'Signes d’anxiété ou de stress',
//         $score <= 40 => 'État dépressif modéré',
//         default => 'État très préoccupant',
//     };

//     // Associer un mot-clé pour Unsplash
//     $keywords = [
//         'Bonne santé mentale' => 'happy mind',
//         'Légère fatigue émotionnelle' => 'calm nature',
//         'Signes d’anxiété ou de stress' => 'stress relief',
//         'État dépressif modéré' => 'dark mood',
//         'État très préoccupant' => 'mental health struggle'
//     ];

//     $keyword = $keywords[$etatMental] ?? 'mental health';
//     $imageUrl = $unsplashService->getImageByKeyword($keyword);

//         // Créer une nouvelle instance de Quiz
//         $quiz = new Quiz();
//         $quiz->setScore($score);
//         $quiz->setEtatMental($etatMental);
//         // $quiz->setImageUrl($imageUrl);
    
//         // Enregistrer le quiz dans la base de données
//         $entityManager->persist($quiz);
//         $entityManager->flush();

//     // Debugging pour voir si l'URL de l'image est récupérée correctement
//     // dd($imageUrl); // Décommentez pour vérifier

//     return $this->render('quiz/result.html.twig', [
//         'score' => $score,
//         'etatMental' => $etatMental,
//         'imageUrl' => $imageUrl
//     ]);
// }

#[Route('/admin/list', name: 'quiz_list_admin', methods: ['GET'])]    
public function list(QuizRepository $quizRepository, Request $request): Response
{
    // Récupérer le terme de recherche depuis l'URL (query parameter 'q')
    $query = $request->query->get('q', '');

    // Récupérer les paramètres de tri depuis l'URL
    $sortBy = $request->query->get('sort_by', 'id'); // Colonne par défaut : 'id'
    $order = $request->query->get('order', 'desc');   // Ordre par défaut : 'asc'

    // Initialiser le query builder
    $queryBuilder = $quizRepository->createQueryBuilder('q');

    // Appliquer la recherche si un terme est fourni
    if (!empty($query)) {
        $queryBuilder
        ->where('q.etatMental LIKE :query OR q.score LIKE :query')
        ->setParameter('query', '%' . $query . '%');
    }

    // Appliquer le tri
    $queryBuilder->orderBy('q.' . $sortBy, $order);

    // Récupérer tous les résultats sans pagination
    // $quizzes = $quizRepository->findAll();
    $quizzes = $queryBuilder->getQuery()->getResult();

    return $this->render('quiz/AdminShowQuiz.html.twig', [
        'quizzes' => $quizzes,
        'searchQuery' => $query, // Passer le terme de recherche au template
        'sort_by' => $sortBy,    // Passer la colonne de tri au template
        'order' => $order,       // Passer l'ordre de tri au template
    ]);
}

#[Route('/list', name: 'quiz_list', methods: ['GET'])]
public function listQuizzes(QuizRepository $quizRepository, PaginatorInterface $paginator, Request $request): Response
{
    // Récupérer tous les quiz
    $query = $quizRepository->createQueryBuilder('q')->getQuery();

    // Paginer les résultats
    $quizzes = $paginator->paginate(
        $query, // Requête à paginer
        $request->query->getInt('page', 1), // Numéro de page par défaut
        6 // Nombre d'éléments par page
    );

    return $this->render('quiz/list.html.twig', [
        'quizzes' => $quizzes,
    ]);
}
#[Route('/quiz/{id}', name: 'quiz_show', methods: ['GET'])]
public function showQuiz(Quiz $quiz, UnsplashService $unsplashService): Response
{
    // Associer un mot-clé pour Unsplash en fonction de l'état mental
    $keywords = [
        'Bonne santé mentale' => 'happy mind',
        'Légère fatigue émotionnelle' => 'calm nature',
        'Signes d’anxiété ou de stress' => 'stress relief',
        'État dépressif modéré' => 'dark mood',
        'État très préoccupant' => 'mental health struggle'
    ];

    $keyword = $keywords[$quiz->getEtatMental()] ?? 'mental health';
    $imageUrl = $unsplashService->getImageByKeyword($keyword);

    return $this->render('quiz/ShowQuiz.html.twig', [
        'quiz' => $quiz,
        'imageUrl' => $imageUrl, // Passer l'URL de l'image au template
    ]);
}

#[Route('/deleteAdmin/{id}', name: 'quiz_delete', methods: ['POST'])]
public function deleteAdmin(Request $request, Quiz $quiz, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('delete'.$quiz->getId(), $request->request->get('_token'))) {
        $entityManager->remove($quiz);
        $entityManager->flush();
    }

    return $this->redirectToRoute('quiz_list_admin');
}

}