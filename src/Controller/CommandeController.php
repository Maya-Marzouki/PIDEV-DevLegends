<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Produit;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use Mollie\Api\MollieApiClient;
use App\Service\MollieService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Service\PdfService;
use Psr\Log\LoggerInterface;

class CommandeController extends AbstractController
{
    private $requestStack;
    private $mollieService;
    private $logger;
    
    public function __construct(RequestStack $requestStack, MollieService $mollieService,LoggerInterface $logger)
    {
        $this->requestStack = $requestStack;
        $this->mollieService = $mollieService;
        $this->logger = $logger;
        
    }
    
    #[Route('/commandeclient', name: 'commandeclient')]
    public function showcommandeclient(CommandeRepository $commandeRepository): Response
    {
        $commandes = $commandeRepository->findAll(); 

        if (!$commandes) {
            throw $this->createNotFoundException('Commande non trouvée.');
        }

        return $this->render('commande/showclientcommande.html.twig', [
            'commandes' => $commandes,
        ]);
    }
    
    
    #[Route('/addcommande', name: 'valider_commande', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, ProduitRepository $produitRepository, PdfService $pdfService): Response
{
    // Récupération du panier depuis la session
    $session = $this->requestStack->getCurrentRequest()->getSession();
    $panier = $session->get('panier', []);

    // Calcul du total du panier
    $total = 0;
    foreach ($panier as $item) {
        $total += $item['prixProduit'] * $item['quantiteProduit'];
    }

    // Création de la commande
    $commande = new Commande();
    if (!$commande->getDateCommande()) {
        $commande->setDateCommande(new \DateTime());
    }

    $form = $this->createForm(CommandeType::class, $commande, [
        'data' => $commande,
        'totalCom' => $total,  // Passer le total calculé dans le formulaire
    ]);

    $form->handleRequest($request);

    $totalCommande = $total;

    // Assigner le total à la commande
    $commande->setTotalCom($totalCommande);

    // Vérification si le montant est valide (plus grand que 0)
    if ($totalCommande <= 0) {
        $this->addFlash('error', 'Le montant total de la commande doit être supérieur à zéro.');
        return $this->redirectToRoute('app_commande');
    }

    // Si le formulaire est soumis et valide, on persiste la commande
    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer le mode de paiement choisi
        $modePaiement = $form->get('modePaiement')->getData();

        // Assigner les autres valeurs de la commande
        $commande->setPays($form->get('pays')->getData());
        $commande->setNumTelephone($form->get('NumTelephone')->getData());

        // Persister la commande dans la base de données
        $entityManager->persist($commande);
        $entityManager->flush();

        $this->addFlash('success', 'Commande validée avec le mode de paiement : ' . $modePaiement);

        // Création du paiement Mollie
        try {
            // Création du paiement Mollie
            $paymentUrl = $this->mollieService->createPayment(
                $totalCommande,
                'Commande #' . $commande->getId(),
                $this->generateUrl('app_commande', [], UrlGeneratorInterface::ABSOLUTE_URL)
            );

            // Log de l'URL de paiement pour Mollie
            $this->logger->info('URL de paiement générée pour la commande ' . $commande->getId() . ': ' . $paymentUrl);

            // Stocker le paymentId dans la commande
            $commande->setPaymentId($this->mollieService->getPaymentId());
            $entityManager->persist($commande);
            $entityManager->flush();

            // Rediriger l'utilisateur vers la page de paiement Mollie
            return $this->redirect($paymentUrl);

        } catch (\RuntimeException $e) {
            // Si une erreur se produit lors de la création du paiement, afficher un message d'erreur
            $this->logger->error('Erreur lors de la création du paiement Mollie : ' . $e->getMessage());
            $this->addFlash('error', 'Erreur lors du paiement, veuillez réessayer.');
            return $this->redirectToRoute('app_commande');
        }
        
        // Après la validation de la commande, générer et afficher la facture PDF
       
    }

    return $this->render('commande/formaddcommande.html.twig', [
        'form' => $form->createView(),
        'total' => $total,
    ]);
}

#[Route('/facture/{commandeId}', name: 'generate_invoice')]
public function generateInvoice(int $commandeId, EntityManagerInterface $entityManager, PdfService $pdfService): Response
{
    // Récupérer la commande par son ID
    $commande = $entityManager->getRepository(Commande::class)->find($commandeId);

    if (!$commande) {
        throw $this->createNotFoundException('Commande non trouvée.');
    }

    // Utilisation du service PdfService pour générer la facture PDF
    $pdfContent = $pdfService->generateInvoice($commande); // Ce service génère la facture en PDF

    // Retourner la réponse PDF
    return new Response(
        $pdfContent,
        200,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="facture_commande_' . $commande->getId() . '.pdf"'
        ]
    );
}   

    #[Route('/produit/stock/{id}', name: 'get_stock', methods: ['GET'])]
    public function getStock(Produit $produit): JsonResponse
    {
        return new JsonResponse(['stock' => $produit->getQteProduit()]);
    }

    #[Route('/commandes', name: 'app_commande', methods: ['GET'])]
    public function showAll(CommandeRepository $commandeRepository): Response
    {
        $commandes = $commandeRepository->findAll();
        
        return $this->render('commande/formshowcommande.html.twig', [
            'commandes' => $commandes, // Note bien 'commandes' au pluriel
        ]);
    }

    #[Route('/commande/edit', name: 'editCommande', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            return $this->redirectToRoute('app_commande');
        }
    
        return $this->render('commande/formeditcommande.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/commande/delete', name: 'deleteCommande', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();

            $this->addFlash('success', 'Commande supprimée avec succès.');
        }

        return $this->redirectToRoute('app_commande');
    }
}
