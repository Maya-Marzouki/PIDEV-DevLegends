<?php

namespace App\Controller;

use App\Entity\Consultation;
use App\Form\ConsultationType;
use App\Repository\ConsultationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Service\PdfGeneratorService;
use App\Service\QrCodeGeneratorService;
use App\Service\QrCodeService;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

#[Route('/consultation')]
class ConsultationController extends AbstractController
{

    private $qrCodeService;
    private $entityManager;

    public function __construct(QrCodeService $qrCodeService, EntityManagerInterface $entityManager)
    {
        $this->qrCodeService = $qrCodeService;
        $this->entityManager = $entityManager;
    }

    #[Route('/qr-code', name: 'consultation_qr_code')]

    public function generateQrCode(): Response
    {
        // Récupérer toutes les consultations depuis la base de données
        $consultations = $this->entityManager
            ->getRepository(Consultation::class)
            ->findAll();

        // Convertir les consultations en tableau pour le QR code
        $consultationData = [];
        foreach ($consultations as $consultation) {
            $consultationData[] = [
                'id' => $consultation->getId(),
                'date' => $consultation->getDateCons()->format('Y-m-d'),
                'nom' => $consultation->getNom(),
                'prenom' => $consultation->getPrenom(),
                'age' => $consultation->getAge(),
                'profession' => $consultation->getLienVisioCons(),
                'notes' => $consultation->getNotesCons(),
            ];
        }
        
        // Debug : Afficher les données des consultations
        dump($consultationData);

        // Générer le QR code avec les données des consultations
        $qrCodeUrl = $this->qrCodeService->generateQrCode($consultationData);

        // Debug : Afficher l'URL du QR code
        dump($qrCodeUrl);

        // Afficher le QR code dans une vue
        return $this->render('consultation/qr_code.html.twig', [
            'qrCodeUrl' => $qrCodeUrl,
        ]);
    }

// Affiche la liste des consultation pour l'Admin
    #[Route('/list', name: 'consultation_index', methods: ['GET'])]
    public function index(ConsultationRepository $consultationRepository, Request $request): Response
    {
        // Récupérer le terme de recherche depuis l'URL (query parameter 'q')
        $query = $request->query->get('q', '');

        // Récupérer les paramètres de tri depuis l'URL
        $sortBy = $request->query->get('sort_by', 'id'); // Colonne par défaut : 'id'
        $order = $request->query->get('order', 'asc');   // Ordre par défaut : 'asc'

        // Initialiser le query builder
        $queryBuilder = $consultationRepository->createQueryBuilder('c');

        // Appliquer la recherche si un terme est fourni
        if (!empty($query)) {
            $queryBuilder
                ->where('c.nom LIKE :query OR c.prenom LIKE :query OR c.notesCons LIKE :query')
                ->setParameter('query', '%' . $query . '%');
        }

        // Appliquer le tri
        $queryBuilder->orderBy('c.' . $sortBy, $order);

        // Récupérer tous les résultats sans pagination
        $consultations = $queryBuilder->getQuery()->getResult();

        return $this->render('consultation/ShowConsultation.html.twig', [
            'consultations' => $consultations,
            'searchQuery' => $query, // Passer le terme de recherche au template
            'sort_by' => $sortBy,    // Passer la colonne de tri au template
            'order' => $order,       // Passer l'ordre de tri au template
        ]);
    }

    // Affiche la liste des consultation pour l'utilisateur
    // #[Route('/view/consultation', name: 'consultation_view')]
    // public function viewConsultation(ConsultationRepository $consultationRepository): Response
    // {
    //     return $this->render('consultation/viewConsultation.html.twig', [
    //         'consultations' => $consultationRepository->findAll(),
    //     ]);
    // }
    #[Route('/view/consultation', name: 'consultation_view', methods: ['GET'])]
    public function viewConsultation(Request $request, ConsultationRepository $consultationRepository): Response
    {
    // Récupérer les paramètres de tri, de recherche et de pagination
    $sortBy = $request->query->get('sort_by', 'dateCons'); // Colonne par défaut : 'dateCons'
    $order = $request->query->get('order', 'asc');         // Ordre par défaut : 'asc'
    $searchQuery = $request->query->get('q', '');          // Terme de recherche
    $page = $request->query->getInt('page', 1);            // Page par défaut : 1
    $limit = 10;                                           // Nombre de résultats par page

    // Créer une requête Query Builder
    $queryBuilder = $consultationRepository->createQueryBuilder('c');

    // Appliquer la recherche si un terme est fourni
    if (!empty($searchQuery)) {
        $queryBuilder
            ->where('c.Nom LIKE :query OR c.prenom LIKE :query OR c.notesCons LIKE :query')
            ->setParameter('query', '%' . $searchQuery . '%');
    }

    // Appliquer le tri
    $queryBuilder->orderBy('c.' . $sortBy, $order);

    // Pagination
    $query = $queryBuilder->getQuery()
        ->setFirstResult(($page - 1) * $limit) // Offset
        ->setMaxResults($limit);               // Limite

    $paginator = new Paginator($query);
    $totalConsultations = count($paginator);   // Nombre total de résultats
    $totalPages = ceil($totalConsultations / $limit); // Nombre total de pages

    return $this->render('consultation/viewConsultation.html.twig', [
        'consultations' => $paginator,
        'searchQuery' => $searchQuery, // Passer le terme de recherche au template
        'sort_by' => $sortBy,          // Passer la colonne de tri au template
        'order' => $order,             // Passer l'ordre de tri au template
        'currentPage' => $page,        // Passer la page actuelle au template
        'totalPages' => $totalPages,   // Passer le nombre total de pages au template
        ]);
    }

    #[Route('/new', name: 'consultation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $consultation = new Consultation();
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);
        dump($request->request->all());

        // Vérification si le formulaire est soumis
        if ($form->isSubmitted()) {
            dump('Formulaire soumis'); // Debug

            // Vérification si le formulaire est valide
            if ($form->isValid()) {
                dump('Formulaire valide'); // Debug
                
                try {
                    $entityManager->persist($consultation);
                    $entityManager->flush();
                    $this->addFlash('success', 'Consultation ajoutée avec succès !');

                    return $this->redirectToRoute('consultation_view');
                } catch (\Exception $e) {
                    dump('Erreur lors de l’enregistrement : ' . $e->getMessage()); // Debug
                    $this->addFlash('error', 'Une erreur est survenue lors de l’ajout.');
                }
            } else {
                dump($form->getErrors(true)); // Debug des erreurs
                $this->addFlash('error', 'Le formulaire contient des erreurs.');
            }
        }

        return $this->render('consultation/addConsultation.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // #[Route('/new', name: 'consultation_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $consultation = new Consultation();
    //     $form = $this->createForm(ConsultationType::class, $consultation);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($consultation);
    //         $entityManager->flush();

    //         $this->addFlash('success', 'Consultation ajoutée avec succès !');
    //         return $this->redirectToRoute('consultation_index');
    //     }

    //     return $this->render('consultation/addConsultation.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    #[Route('/edit/{id}', name: 'edit_consultation', methods: ['GET', 'POST'])]
    public function edit(Request $request, Consultation $consultation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('consultation_view');
        }

        return $this->render('consultation/editConsultation.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/editAdmin/{id}', name: 'edit_consultation_Admin', methods: ['GET', 'POST'])]
    public function editAdmin(Request $request, Consultation $consultation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('consultation_index');
        }

        return $this->render('consultation/editAdmin.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete_consultation', methods: ['POST'])]
    public function delete(Request $request, Consultation $consultation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$consultation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($consultation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('consultation_view');
    }

    #[Route('/deleteAdmin/{id}', name: 'delete_consultation_Admin', methods: ['POST'])]
    public function deleteAdmin(Request $request, Consultation $consultation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$consultation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($consultation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('consultation_index');
    }

    // Création d'une route pour la validation AJAX
    #[Route('/validate-date', name: 'validate_date', methods: ['POST'])]
    public function validateDate(Request $request, ValidatorInterface $validator): Response
    {
        $date = $request->request->get('date');

        if (!$date) {
            return $this->json(['error' => 'La date est requise.'], 400);
        }

        $dateObject = \DateTime::createFromFormat('Y-m-d', $date);

        if (!$dateObject) {
            return $this->json(['error' => 'Format de date invalide.'], 400);
        }

        // Validation avec Symfony Validator
        $errors = $validator->validate($dateObject, [
            new Assert\NotNull(['message' => 'La date de consultation est obligatoire.']),
            new Assert\GreaterThanOrEqual(['value' => 'today', 'message' => 'La date ne peut pas être antérieure à aujourd’hui.']),
        ]);

        if (count($errors) > 0) {
            return $this->json(['error' => $errors[0]->getMessage()], 400);
        }

        return $this->json(['success' => 'Date valide.']);
    }

    #[Route('/consultation/export-pdf', name: 'consultation_export_pdf')]
    public function exportPdf(ConsultationRepository $consultationRepository, PdfGeneratorService $pdfGenerator): Response
    {
        // Récupérer toutes les consultations
        $consultations = $consultationRepository->findAll();

        // Générer le HTML pour le PDF
        $html = $this->renderView('consultation/pdf_template.html.twig', [
            'consultations' => $consultations,
        ]);

        // Générer le PDF
        $pdfContent = $pdfGenerator->generatePdfFromHtml($html);

        // Retourner le PDF en tant que réponse
        return new Response(
            $pdfContent,
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="consultations.pdf"',
            ]
        );
    }

    // #[Route('/consultation/qr-code', name: 'consultation_qr_code')]
    // public function generateQrCode(ConsultationRepository $consultationRepository, QrCodeGeneratorService $qrCodeGenerator): Response
    // {
    //     // Récupérer toutes les consultations
    //     $consultations = $consultationRepository->findAll();

    //     // Formater les consultations en une chaîne de caractères
    //     $data = "Liste des Consultations:\n\n";
    //     foreach ($consultations as $consultation) {
    //         $data .= sprintf(
    //             "ID: %d\nDate: %s\nNom: %s\nPrénom: %s\nÂge: %d\nProfession: %s\nRaison: %s\n\n",
    //             $consultation->getId(),
    //             $consultation->getDateCons()->format('Y-m-d'),
    //             $consultation->getNom(),
    //             $consultation->getPrenom(),
    //             $consultation->getAge(),
    //             $consultation->getLienVisioCons(),
    //             $consultation->getNotesCons()
    //         );
    //     }

    //     // Générer le QR code
    //     $qrCode = $qrCodeGenerator->generateQrCode($data);

    //     // Retourner le QR code en tant que réponse
    //     return new Response(
    //         $qrCode,
    //         Response::HTTP_OK,
    //         [
    //             'Content-Type' => 'image/png',
    //             // permettre à l'utilisateur de télécharger le QR code
    //             'Content-Disposition' => 'attachment; filename="consultations_qr_code.png"',
    //             // 'Content-Disposition' => 'inline; filename="consultations_qr_code.png"',
    //         ]
    //     );
    // }
}
