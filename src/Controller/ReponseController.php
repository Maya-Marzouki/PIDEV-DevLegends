<?php
namespace App\Controller;

use App\Entity\Reponse;
use App\Form\ReponseQuizType;
use App\Repository\ReponseRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use App\Service\PdfGeneratorService;

class ReponseController extends AbstractController
{
    // Liste des réponses
    #[Route('/reponse/list', name: 'reponse_index')]
    public function index(ReponseRepository $reponseRepository): Response
    {
        $reponses = $reponseRepository->findAll();
        return $this->render('reponse/listReponse.html.twig', [
            'reponses' => $reponses,
        ]);
    }

    
    #[Route('/admin/reponse', name: 'app_reponse_index_admin')]
    public function adminRepIndex(ReponseRepository $reponses, Request $request): Response
    {
        // Récupérer le terme de recherche depuis l'URL (query parameter 'q')
        $query = $request->query->get('q', '');

        // Récupérer les paramètres de tri depuis l'URL
        $sortBy = $request->query->get('sort_by', 'id'); // Colonne par défaut : 'id'
        $order = $request->query->get('order', 'asc');   // Ordre par défaut : 'asc'

        // Initialiser le query builder
        $queryBuilder = $reponses->createQueryBuilder('r')
            ->leftJoin('r.question', 'q'); // Jointure avec l'entité Question

        // Appliquer la recherche si un terme est fourni
        if (!empty($query)) {
            $queryBuilder
                ->where('r.answerText LIKE :query OR r.score LIKE :query OR q.questionText LIKE :query')
                ->setParameter('query', '%' . $query . '%');
        }

        // Appliquer le tri
        $queryBuilder->orderBy('r.' . $sortBy, $order);

        // Récupérer tous les résultats sans pagination
        $reponses = $queryBuilder->getQuery()->getResult();

        return $this->render('reponse/viewBackReponse.html.twig', [
            'reponses' => $reponses,
            'searchQuery' => $query, // Passer le terme de recherche au template
            'sort_by' => $sortBy,    // Passer la colonne de tri au template
            'order' => $order,       // Passer l'ordre de tri au template
        ]);
    }

    // Créer une nouvelle réponse
    #[Route('/reponse/new', name: 'reponse_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, QuestionRepository $questionRepository): Response
    {
        // Tester la récupération des questions
        $questions = $questionRepository->findAll();
        dump($questions); // Vérifier que les questions sont bien récupérées

        $reponse = new Reponse();
        $form = $this->createForm(ReponseQuizType::class, $reponse, [
            'questions' => $questions, // Passer les questions au formulaire
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            dump($form->getData()); // Afficher les données du formulaire
            dump($form->getErrors(true)); // Afficher les erreurs de validation
            
            if ($form->isValid()) {
            $entityManager->persist($reponse);
            $entityManager->flush();
            $this->addFlash('success', 'Réponse ajoutée avec succès !');
            return $this->redirectToRoute('app_reponse_index_admin');
        } else {
            $this->addFlash('danger', 'Veuillez corriger les erreurs du formulaire.');
        }
        }
        return $this->render('reponse/addReponse.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Modifier une réponse existante
    #[Route('/reponse/{id}/edit', name: 'reponse_edit')]
    public function edit(Request $request, Reponse $reponse, EntityManagerInterface $entityManager, QuestionRepository $questionRepository): Response
    {
        $questions = $questionRepository->findAll(); // Récupérer toutes les questions
    
        $form = $this->createForm(ReponseQuizType::class, $reponse, [
            'questions' => $questions, // Passer les questions au formulaire
        ]);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Réponse modifiée avec succès !');
            return $this->redirectToRoute('app_reponse_index_admin');
        }
    
        return $this->render('reponse/editReponse.html.twig', [
            'form' => $form->createView(),
            'reponse' => $reponse,
        ]);
    }

        // Supprimer une réponse (avec protection CSRF)
        #[Route('/reponse/{id}/delete', name: 'reponse_delete', methods: ['POST'])]
        public function delete(Request $request, Reponse $reponse, EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager): Response
        {
            $submittedToken = $request->request->get('_token');
    
            if (!$csrfTokenManager->isTokenValid(new CsrfToken('delete_reponse', $submittedToken))) {
                $this->addFlash('danger', 'Token CSRF invalide.');
                return $this->redirectToRoute('reponse_index');
            }
    
            try {
                $entityManager->remove($reponse);
                $entityManager->flush();
                $this->addFlash('success', 'Réponse supprimée avec succès !');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Erreur lors de la suppression.');
            }
    
            return $this->redirectToRoute('reponse_index');
        }

        #[Route('/admin/reponse/export-pdf', name: 'app_reponse_export_pdf')]
        public function exportPdf(ReponseRepository $reponseRepository, PdfGeneratorService $pdfGenerator): Response
        {
            // Récupérer toutes les réponses
            $reponses = $reponseRepository->findAll();
    
            // Générer le HTML pour le PDF
            $html = $this->renderView('reponse/pdf_template.html.twig', [
                'reponses' => $reponses,
            ]);
    
            // Générer le PDF
            $pdfContent = $pdfGenerator->generatePdfFromHtml($html);
    
            // Retourner le PDF en tant que réponse
            return new Response(
                $pdfContent,
                Response::HTTP_OK,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="reponses.pdf"',
                ]
            );
        }

    // // Supprimer une réponse
    // #[Route('/reponse/{id}/delete', name: 'reponse_delete')]
    // public function delete(Reponse $reponse, EntityManagerInterface $entityManager): Response
    // {
    //     $entityManager->remove($reponse);
    //     $entityManager->flush();

    //     return $this->redirectToRoute('reponse_index');
    // }
}
