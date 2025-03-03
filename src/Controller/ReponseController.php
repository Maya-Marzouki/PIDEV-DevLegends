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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Knp\Component\Pager\PaginatorInterface;

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

    // #[Route('/reponse/list', name: 'reponse_index')]
    // public function index(ReponseRepository $reponseRepository, PaginatorInterface $paginator, Request $request): Response
    // {
    //     $search = $request->query->get('search', '');
    //     $searchQuery = $request->query->get('q', '');

    //     $queryBuilder = $reponseRepository->searchRep($search); // Maintenant, c'est un QueryBuilder
    //     $page = max(1, $request->query->getInt('page', 1)); // Assure que la page est au moins 1

    //     $limit = 5; // Nombre de résultats par page


    // // Récupérer le total des consultations AVANT la pagination
    // $totalConsultations = count($queryBuilder->getQuery()->getResult());
    // $totalPages = max(1, ceil($totalConsultations / $limit)); // Évite d'avoir 0 page

    // // Appliquer la pagination
    // $query = $queryBuilder->getQuery()
    //                       ->setFirstResult(($page - 1) * $limit) // Offset
    //                       ->setMaxResults($limit);               // Limit
        
    //                       $reponses = $query->getResult(); // Exécute la requête paginée

    //                       return $this->render('reponse/listReponse.html.twig', [
    //                         'reponses' => $reponses, // Résultats paginés
    //                           'searchQuery' => $searchQuery, // Terme de recherche
    //                           'currentPage' => $page, // Page actuelle
    //                           'totalPages' => $totalPages, // Nombre total de pages
    //                       ]);

    // }



    // #[Route('/admin/reponse', name: 'app_reponse_index_admin')]
    // public function adminRepIndex(ReponseRepository $reponseRepository): Response
    // {
    //     $reponses = $reponseRepository->findAll();
    //     return $this->render('reponse/viewBackReponse.html.twig', [
    //         'reponses' => $reponses,
    //     ]);
    // }

    // #[Route('/admin/reponse', name: 'app_reponse_index_admin')]
    // public function adminRepIndex(ReponseRepository $reponseRepository, Request $request): Response
    // {
    //     // Récupérer les paramètres de recherche et de tri
    //     $searchQuery = $request->query->get('q', ''); // Paramètre de recherche
    //     $sortBy = $request->query->get('sort_by', 'id'); // Colonne de tri (par défaut : 'id')
    //     $order = $request->query->get('order', 'asc'); // Ordre de tri (par défaut : 'asc')
    
    //     // Créer le QueryBuilder pour filtrer et trier les réponses
    //     $queryBuilder = $reponseRepository->createQueryBuilder('r');
    
    //     // Appliquer la recherche
    //     if (!empty($searchQuery)) {
    //         $queryBuilder->where('r.contenu LIKE :query') // Recherche dans le contenu de la réponse
    //                      ->setParameter('query', '%' . $searchQuery . '%');
    //     }
    
    //     // Appliquer le tri
    //     $queryBuilder->orderBy('r.' . $sortBy, $order);
    
    //     // Exécuter la requête
    //     $reponses = $queryBuilder->getQuery()->getResult();
    
    //     // Si c'est une requête AJAX, renvoyer uniquement le tableau des résultats
    //     if ($request->isXmlHttpRequest()) {
    //         return $this->render('reponse/_reponse_table.html.twig', [
    //             'reponses' => $reponses,
    //         ]);
    //     }
    
    //     // Sinon, renvoyer la page complète
    //     return $this->render('reponse/viewBackReponse.html.twig', [
    //         'reponses' => $reponses,
    //         'searchQuery' => $searchQuery,
    //         'sort_by' => $sortBy,
    //         'order' => $order,
    //     ]);
    // }

    
    #[Route('/admin/reponse', name: 'app_reponse_index_admin')]
    public function adminRepIndex(ReponseRepository $reponses, Request $request): Response
    {
        // Récupérer le terme de recherche depuis l'URL (query parameter 'q')
        $query = $request->query->get('q', '');
    
        // Initialiser le query builder
        $queryBuilder = $reponses->createQueryBuilder('r')
            ->leftJoin('r.question', 'q'); // Jointure avec l'entité Question
    
        // Appliquer la recherche si un terme est fourni
        if (!empty($query)) {
            $queryBuilder
                ->where('r.answerText LIKE :query OR r.score LIKE :query OR q.questionText LIKE :query')
                ->setParameter('query', '%' . $query . '%');
        }
    
        // Récupérer tous les résultats sans pagination
        $reponses = $queryBuilder->getQuery()->getResult();
    
        return $this->render('reponse/viewBackReponse.html.twig', [
            'reponses' => $reponses,
            'searchQuery' => $query, // Passer le terme de recherche au template
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
        public function exportPdf(ReponseRepository $reponseRepository, PdfGeneratorService $pdfGenerator, UrlGeneratorInterface $urlGenerator): Response
        {
            // Récupérer toutes les réponses
            $reponses = $reponseRepository->findAll();

            // Générer l'URL absolue du site
            $baseUrl = $urlGenerator->generate('app_reponse_index_admin', [], UrlGeneratorInterface::ABSOLUTE_URL);

            // Lire l'image du logo et la convertir en Base64
            $logoPath = $this->getParameter('kernel.project_dir') . '/public/assets/images/logoinnerbloom1.png';
            $imagePath = $this->getParameter('kernel.project_dir') . '/public/assets/images/carousel-1.jpeg';

            $logoBase64 = '';
            $imageBase64 = '';

            
            if (file_exists($logoPath)) {
                $logoData = file_get_contents($logoPath);
                $logoBase64 = base64_encode($logoData);
            }
            
                // Convertir l'image avant la liste en Base64
                if (file_exists($imagePath)) {
                    $imageData = file_get_contents($imagePath);
                    $imageBase64 = base64_encode($imageData);
                }

            // Générer le HTML du template avec les variables nécessaires
            $html = $this->renderView('reponse/pdf_template.html.twig', [
                'base_url' => $baseUrl,
                'logo_base64' => $logoBase64,
                'image_base64' => $imageBase64,
                'reponses' => $reponses,
            ]);

            // Générer le PDF et le retourner en réponse
            return new Response($pdfGenerator->generatePdfFromHtml($html), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="reponses.pdf"',
            ]);
        }

            // // Récupérer toutes les réponses
            // $reponses = $reponseRepository->findAll();
        
            // // Générer un chemin absolu pour l'image
            // $logoPath = $this->getParameter('kernel.project_dir') . '/public/admin/images/logoinnerbloom.png';
            // if (!file_exists($logoPath)) {
            //     throw new \Exception("Le fichier logo n'existe pas : " . $logoPath);
            // }
        
            // // Chemin absolu pour DomPDF
            // $logoUrl = $urlGenerator->generate('homepage', [], UrlGeneratorInterface::ABSOLUTE_URL) . 'admin/images/logoinnerbloom.png';
        
            // // Générer le HTML pour le PDF
            // $html = $this->renderView('reponse/pdf_template.html.twig', [
            //     'logo_url' => $logoUrl,
            //     'reponses' => $reponses,
            // ]);
        
            // return $pdfGenerator->generatePdfResponse($html);


    //     {
    //             // Obtenez le chemin absolu du fichier image dans le répertoire public
    // $logoPath = $this->getParameter('kernel.project_dir') . '/assets/admin/images/logoinnerbloom.png';

    // // Vérifiez si le fichier existe avant de tenter de le lire
    // if (!file_exists($logoPath)) {
    //     throw new \Exception('Le fichier logo est introuvable.');
    // }

    // // Convertir l'image en base64
    // $logoBase64 = base64_encode(file_get_contents($logoPath));
    //         // Récupérer toutes les réponses
    //         $reponses = $reponseRepository->findAll();

    //         // Générer le HTML pour le PDF
    //         $html = $this->renderView('reponse/pdf_template.html.twig', [
    //             'logo_base64' => $logoBase64,
    //             'reponses' => $reponses,
    //         ]);
    
    //         // Générer le PDF
    //         $pdfContent = $pdfGenerator->generatePdfFromHtml($html);
    
    //         // Retourner le PDF en tant que réponse
    //         return new Response(
    //             $pdfContent,
    //             Response::HTTP_OK,
    //             [
    //                 'Content-Type' => 'application/pdf',
    //                 'Content-Disposition' => 'attachment; filename="reponses.pdf"',
    //             ]
    //         );
    //     }

    // // Supprimer une réponse
    // #[Route('/reponse/{id}/delete', name: 'reponse_delete')]
    // public function delete(Reponse $reponse, EntityManagerInterface $entityManager): Response
    // {
    //     $entityManager->remove($reponse);
    //     $entityManager->flush();

    //     return $this->redirectToRoute('reponse_index');
    // }
}
