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

class CommandeController extends AbstractController
{
    private $requestStack;
    private $mollieService;
    
    public function __construct(RequestStack $requestStack, MollieService $mollieService)
    {
        $this->requestStack = $requestStack;
        $this->mollieService = $mollieService;
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
    public function new(Request $request, EntityManagerInterface $entityManager, ProduitRepository $produitRepository): Response
    { 
        // Récupération du panier depuis la session
        $session = $this->requestStack->getCurrentRequest()->getSession();  
        $panier = $session->get('panier', []);
        
        // Calcul du total du panier
        $total = 0;
        foreach ($panier as $item) {
            $total += $item['prixProduit'] * $item['quantiteProduit'];
        }
        
        $commande = new Commande();
        
        if (!$commande->getDateCommande()) {
            $commande->setDateCommande(new \DateTime());
        }
        
        $form = $this->createForm(CommandeType::class, $commande, [
            'data' => $commande,
            'totalCom' => $total,  // Passer le total calculé dans le formulaire
        ]);
        
        $form->handleRequest($request);
        
        // Vérification du totalCommande basé sur les produits de la commande
        $totalCommande = $total;  // Utiliser le total calculé du panier

        // Assigner le total à la commande
        $commande->setTotalCom($totalCommande);

        // Vérification si le montant est valide (plus grand que 0)
        if ($totalCommande <= 0) {
            $this->addFlash('error', 'Le montant total de la commande doit être supérieur à zéro.');
            return $this->redirectToRoute('app_commande');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $modePaiement = $form->get('modePaiement')->getData();
            $numeroCarte = $form->get('numeroCarte')->getData();
            $numeroVirement = $form->get('numeroVirement')->getData();
            $paypalEmail = $form->get('paypalEmail')->getData();
        
            if ($modePaiement === 'carte') {
                // Traitement paiement par carte
            } elseif ($modePaiement === 'virement') {
                // Traitement paiement par virement
            } elseif ($modePaiement === 'paypal') {
                // Traitement paiement PayPal
            }
            
            $pays = $form->get('pays')->getData();
            $numTelephone = $form->get('NumTelephone')->getData();

            // Assigner les valeurs des nouveaux champs à la commande
            $commande->setPays($pays);
            $commande->setNumTelephone($numTelephone);

            // Persister la commande
            $entityManager->persist($commande);
            $entityManager->flush();

            $this->addFlash('success', 'Commande validée avec le mode de paiement : ' . $modePaiement);

            // Création d'un paiement Mollie
            $paymentUrl = $this->mollieService->createPayment(
                $totalCommande, 
                'Commande #' . $commande->getId(),
                $this->generateUrl('app_commande', [], UrlGeneratorInterface::ABSOLUTE_URL)
            );

            // Redirection vers la page de paiement Mollie
            return $this->redirect($paymentUrl);
        } else {
            // Si le formulaire n'est pas valide, affiche les erreurs dans la console
            dump($form->getErrors(true));  // Debugging des erreurs de formulaire
        }

        return $this->render('commande/formaddcommande.html.twig', [
            'form' => $form->createView(),
            'total' => $total,
        ]);
    }

    #[Route('/mollie/success', name: 'mollie_success')]
    public function mollieSuccess(Request $request, EntityManagerInterface $entityManager): Response
    {
        $paymentId = $request->query->get('payment_id');
        
        if (!$paymentId) {
            throw $this->createNotFoundException('Paiement non trouvé.');
        }

        // Ici, on peut mettre à jour la commande (paiement validé)
        // Exemple : $commande->setStatut('payé');

        $this->addFlash('success', 'Paiement réussi !');

        return $this->redirectToRoute('app_commande');
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
