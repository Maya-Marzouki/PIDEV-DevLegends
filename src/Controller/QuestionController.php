<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

#[Route('/admin/question')]
class QuestionController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Afficher la liste des questions
     */

     #[Route('/list', name: 'question_index', methods: ['GET'])]
     public function index(Request $request, QuestionRepository $questionRepository, PaginatorInterface $paginator): Response
     {
         $text = $request->query->get('text', '');
         $type = $request->query->get('type', '');
         $page = max(1, $request->query->getInt('page', 1)); // Toujours 1 minimum
         $limit = 5; // Nombre de questions par page
     
         // Créer le QueryBuilder pour la recherche
         $queryBuilder = $questionRepository->createQueryBuilder('q');
     
         if (!empty($text)) {
             $queryBuilder->andWhere('q.questionText LIKE :text')
                          ->setParameter('text', '%' . $text . '%');
         }
     
         if (!empty($type)) {
             $queryBuilder->andWhere('q.answerType = :type')
                          ->setParameter('type', $type);
         }
     
         // Récupérer le nombre total de questions AVANT pagination
         $totalQuestions = count($queryBuilder->getQuery()->getResult());
         $totalPages = max(1, ceil($totalQuestions / $limit)); // Évite d'avoir 0 pages
     
         // Appliquer la pagination
         $query = $queryBuilder->getQuery()
                               ->setFirstResult(($page - 1) * $limit)
                               ->setMaxResults($limit);
     
         $questions = $query->getResult(); // Récupérer les résultats paginés
     
         return $this->render('question/listQuestion.html.twig', [
             'questions' => $questions,
             'text' => $text,
             'type' => $type,
             'currentPage' => $page,
             'totalPages' => $totalPages,
         ]);
     }
     

    // #[Route('/list', name: 'question_index', methods: ['GET'])]
    // public function index(QuestionRepository $questionRepository): Response
    // {
    //     return $this->render('question/listQuestion.html.twig', [
    //         'questions' => $questionRepository->findAll(),
    //     ]);
    // }

    /**
     * Ajouter une nouvelle question
     */
    #[Route('/new', name: 'question_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($question);
            $this->entityManager->flush();

            $this->addFlash('success', 'Question ajoutée avec succès !');
            return $this->redirectToRoute('question_index');
        }

        return $this->render('question/addQuestion.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Modifier une question
     */
    #[Route('/{id}/edit', name: 'question_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Question $question): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Question mise à jour avec succès !');
            return $this->redirectToRoute('question_index');
        }

        return $this->render('question/editQuestion.html.twig', [
            'form' => $form->createView(),
            'question' => $question,
        ]);
    }

    /**
     * Supprimer une question
     */
    #[Route('/{id}/delete', name: 'question_delete', methods: ['POST'])]
    public function delete(Request $request, Question $question): Response
    {
        if ($this->isCsrfTokenValid('delete' . $question->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($question);
            $this->entityManager->flush();

            $this->addFlash('success', 'Question supprimée avec succès.');
        }

        return $this->redirectToRoute('question_index');
    }
}
