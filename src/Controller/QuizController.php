<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Form\QuizType;
use App\Form\QuizUserResponseType;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


// #[Route('/quiz')]
final class QuizController extends AbstractController
{
    private $entityManager;

    // Injecter EntityManagerInterface dans le constructeur
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/admin/quiz/list', name: 'quiz_index', methods: ['GET'])]
    public function index(QuizRepository $quizRepository): Response
    {
        return $this->render('quiz/ShowQuiz.html.twig', [
            'quizzes' => $quizRepository->findAll(),
        ]);
    }

    // #[Route('/new', name: 'quiz_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $em): Response
    // {
    //     $quiz = new Quiz();
    //     $form = $this->createForm(QuizType::class, $quiz);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // Analyser les réponses et attribuer un état de santé
    //         $etat = $this->analyserReponses($quiz);
    //         $this->addFlash('info', 'Votre état mental : ' . $etat);

    //         $em->persist($quiz);
    //         $em->flush();

    //         return $this->redirectToRoute('quiz_index');
    //     }

    //     return $this->render('quiz/addQuiz.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // PERMET D'INITIALISER UN QUIZ VIDE!!!!!!!!!!!!!!!!!!
    // #[Route('/admin/quiz/new', name: 'quiz_new', methods: ['GET', 'POST'])]
    // public function new(Request $request): Response
    // {
    //     $quiz = new Quiz();
    //     $form = $this->createForm(QuizType::class, $quiz);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // Calcul du score en fonction de la réponse donnée
    //         $reponseQuiz = $quiz->getReponsesQuiz();
    //         if ($reponseQuiz === 'oui') {
    //             $quiz->setScoreQuiz(5);
    //         } elseif ($reponseQuiz === 'parfois') {
    //             $quiz->setScoreQuiz(3);
    //         } else {
    //             $quiz->setScoreQuiz(0);
    //         }

    //         // // Si le formulaire est valide, enregistrez dans la base de données
    //         // $entityManager = $this->getDoctrine()->getManager();
    //         // $entityManager->persist($quiz);
    //         // $entityManager->flush();

    //         // Si le formulaire est valide, enregistrez dans la base de données
    //         $this->entityManager->persist($quiz);
    //         $this->entityManager->flush();

    //         // Redirection après enregistrement
    //         return $this->redirectToRoute('quiz_index', ['id' => $quiz->getId()]);
    //     }

    //     return $this->render('quiz/addQuiz.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // private function analyserReponses(Quiz $quiz): string
    // {
    //     if ($quiz->getScoreQuiz() >= 8) {
    //         return 'Heureux 😊';
    //     } elseif ($quiz->getScoreQuiz() >= 5) {
    //         return 'Neutre 😐';
    //     } else {
    //         return 'Malheureux 😞';
    //     }
    // }


//     #[Route('/admin/quiz/ajouter', name: 'quiz_add')]
// public function addQuiz(Request $request, EntityManagerInterface $em): Response
// {
//     // Vérifier si un quiz existe déjà (par exemple, récupérer par ID si nécessaire)
//     $quiz = new Quiz();

//     // Créer le formulaire pour ajouter une question avec des réponses
//     $form = $this->createForm(QuizType::class, $quiz);

//     $form->handleRequest($request);

//     if ($form->isSubmitted() && $form->isValid()) {
//         // Si le formulaire est soumis et valide, enregistrer le quiz et ses questions
//         $em->persist($quiz);
//         $em->flush();

//         // Afficher un message flash pour confirmer l'ajout
//         $this->addFlash('success', 'Quiz et question(s) ajoutée(s) avec succès !');

//         // Rediriger vers la page d'ajout de questions ou liste des quizzes
//         return $this->redirectToRoute('quiz_index');
//     }

//     // Si le formulaire n'est pas encore soumis, afficher le formulaire d'ajout de quiz et questions
//     return $this->render('quiz/addQuiz.html.twig', [
//         'form' => $form->createView(),
//     ]);
// }

// #[Route('/admin/quiz/ajouter', name: 'quiz_add')]
// public function addQuiz(Request $request, EntityManagerInterface $em): Response
// {
//     // Création d'un nouveau quiz
//     $quiz = new Quiz();

//     // Formulaire pour le Quiz (questions, catégories, etc.)
//     $form = $this->createForm(QuizType::class, $quiz);

//     // Ajouter une question avec ses réponses
//     $questions = $request->get('questions', []);

//     // Gestion de la soumission du formulaire
//     $form->handleRequest($request);

//     if ($form->isSubmitted() && $form->isValid()) {
//         // Récupérer la question et les réponses du formulaire
//         $questionQuiz = $quiz->getQuestionQuiz();
//         $categorieSant = $quiz->getCategorieSant();
        
//         // Ajouter les réponses et points au format JSON
//         $reponsesQuiz = [];
//         foreach ($questions as $questionData) {
//             if (!empty($questionData['reponse']) && isset($questionData['points'])) {
//                 $reponsesQuiz[] = [
//                     'reponse' => $questionData['reponse'],
//                     'points' => (int)$questionData['points']
//                 ];
//             }
//         }
        
//         $quiz->setReponsesQuiz($reponsesQuiz);

//         // Calcul du score total
//         $score = array_sum(array_column($reponsesQuiz, 'points'));
//         $quiz->setScoreQuiz($score);

//         // Sauvegarder le quiz dans la base de données
//         $em->persist($quiz);
//         $em->flush();

//         // Message de succès et redirection
//         $this->addFlash('success', 'Quiz et question ajoutés avec succès !');
//         return $this->redirectToRoute('quiz_index');
//     }

//     return $this->render('quiz/addQuiz.html.twig', [
//         'form' => $form->createView(),
//     ]);
// }


    // #[Route('/admin/{id}/edit', name: 'quiz_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Quiz $quiz, EntityManagerInterface $em): Response
    // {
    //     $form = $this->createForm(QuizType::class, $quiz);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $etat = $this->analyserReponses($quiz);
    //         $this->addFlash('info', 'Nouvel état mental : ' . $etat);

    //         $em->flush();

    //         return $this->redirectToRoute('quiz_index');
    //     }
    //     return $this->render('quiz/editQuiz.html.twig', [
    //         'form' => $form->createView(),
    //         'quiz' => $quiz,
    //     ]);
    // }

    #[Route('/{id}', name: 'quiz_delete', methods: ['POST'])]
    public function delete(Request $request, Quiz $quiz, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $quiz->getId(), $request->request->get('_token'))) {
            $em->remove($quiz);
            $em->flush();
            $this->addFlash('success', 'Quiz supprimé avec succès.');
        }

        return $this->redirectToRoute('quiz_index');
    }

    // // Page pour ajouter une question et ses réponses
    // #[Route('/admin/quiz/ajouter', name: 'quiz_add')]
    // public function addQuiz(Request $request, EntityManagerInterface $em): Response
    // {
    //     // Création d'un nouvel objet Quiz
    //     $quiz = new Quiz();

    //     // Création du formulaire pour ajouter une question avec des réponses
    //     $form = $this->createForm(QuizType::class, $quiz);

    //     $form->handleRequest($request);

    //     // Si le formulaire est soumis et valide, enregistrer les données
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $em->persist($quiz);
    //         $em->flush();

    //         // Rediriger l'utilisateur vers la page où les questions sont ajoutées avec un message de succès
    //         $this->addFlash('success', 'Question ajoutée avec succès !');
    //         return $this->redirectToRoute('quiz_add');
    //     }
    //     return $this->render('quiz/addQuiz.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // Page pour que l'utilisateur réponde au quiz
    #[Route('/user/quiz/start', name: 'quiz_start')]
    public function startQuiz(Request $request, EntityManagerInterface $em): Response
{
    $questions = $em->getRepository(Quiz::class)->findAll();  // Récupérer toutes les questions du quiz

    $quiz = new Quiz();
    $form = $this->createForm(QuizUserResponseType::class, $quiz, [
        'questions' => $questions  // Passer les questions au formulaire
    ]);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $score = 0;

        // Calculer le score total basé sur les réponses de l'utilisateur
        foreach ($questions as $question) {
            $answer = $form->get('question_'.$question->getId())->getData();
            $score += $answer;
        }

        // Déterminer l'état de santé mentale basé sur le score
        $etatMental = $this->interpretScore($score);

        return $this->render('quiz/result.html.twig', [
            'etatMental' => $etatMental,
            'score' => $score,
        ]);
    }

    return $this->render('quiz/quizForm.html.twig', [
        'form' => $form->createView(),
    ]);
}

// Méthode pour interpréter le score et déterminer l'état mental
private function interpretScore(int $score): string
{
    if ($score <= 10) {
        return 'Bonne santé mentale';
    } elseif ($score <= 20) {
        return 'Légère fatigue émotionnelle';
    } elseif ($score <= 30) {
        return 'Signes d’anxiété ou de stress';
    } elseif ($score <= 40) {
        return 'État dépressif modéré';
    } else {
        return 'État très préoccupant';
    }
}


#[Route('/admin/quiz/question', name: 'add_question')]
public function addQuestion(Request $request, EntityManagerInterface $entityManager): Response
{
    $errorMessage = '';



    // Récupérer les données du formulaire
    $question = $request->request->get('question');
    $categorieSant = $request->request->get('categorieSant', '');
    $reponses = $request->request->get('reponses', []);

    dump($request->request->all()); // Teste si les données sont bien envoyées
    exit(); // Stoppe l'exécution pour vérifier

    // Vérifier si la question est vide
    if (empty($question)) {
        $errorMessage = 'La question ne peut pas être vide.';
    }

    if (empty($errorMessage)) {
        $quiz = new Quiz();
        $quiz->setQuestionQuiz($question);
        $quiz->setCategorieSant($categorieSant);
        $quiz->setReponsesQuiz($reponses);
        $quiz->setScoreQuiz(array_sum($reponses));

        $entityManager->persist($quiz);
        $entityManager->flush();

        return $this->redirectToRoute('list_question');
    }

    return $this->render('quiz/addQuestion.html.twig', [
        'errorMessage' => $errorMessage,
        'question' => $question,
        'reponses' => $reponses
    ]);
}



#[Route('/admin/quiz/listquestion', name: 'list_question')]
public function listQuestions(QuizRepository $quizRepository): Response
{
    $questions = $quizRepository->findAll();

    return $this->render('quiz/listQuestions.html.twig', [
        'questions' => $questions
    ]);
}






// DEJA INTERPRETER DANS startQuiz
// // Route pour afficher les résultats du quiz (vue utilisateur)
// #[Route('/user/quiz/result', name: 'quiz_result')]
// public function showResult(Request $request, EntityManagerInterface $em): Response
// {
//     // Récupérer toutes les questions du quiz depuis la base de données
//     $questions = $em->getRepository(Quiz::class)->findAll();  // Récupère toutes les questions

//     // Initialiser le score à 0
//     $score = 0;

//     // Récupérer les réponses de l'utilisateur
//     foreach ($questions as $question) {
//         // Chaque question porte une réponse correspondant à son identifiant
//         $answer = $request->get('question_' . $question->getId()); // Récupère la réponse soumise pour chaque question
//         if ($answer !== null) {
//             // Ajouter la valeur des points associés à la réponse de l'utilisateur
//             $score += (int) $answer;
//         }
//     }

//     // Interpréter le score pour déterminer l'état mental
//     $etatMental = $this->interpretScore($score);

//     // Retourner les résultats à la vue
//     return $this->render('quiz/result.html.twig', [
//         'etatMental' => $etatMental,  // L'état mental de l'utilisateur
//         'score' => $score,  // Le score total de l'utilisateur
//     ]);
// }

}