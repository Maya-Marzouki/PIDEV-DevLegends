<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    // Afficher la liste des commandes
    #[Route('/commande', name: 'app_commande')]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/formshowcommande.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    // Afficher les commandes pour le client
    #[Route('/commandeclient', name: 'commandeclient')]
    public function showcommandeclient(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/showclientcommande.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    // Route pour ajouter une commande depuis le panier
    
    #[Route('/addcommande', name: 'valider_commande', methods: ['GET' ,'POST'])]
public function validerCommande(
    RequestStack $requestStack, 
    EntityManagerInterface $entityManager, 
    ProduitRepository $produitRepository
): Response {
    $session = $requestStack->getCurrentRequest()->getSession();
    $panier = $session->get('panier', []);

    if (empty($panier)) {
        $this->addFlash('error', 'Votre panier est vide.');
        return $this->redirectToRoute('panier');
    }

    // Création d'une nouvelle commande
    $commande = new Commande();
    $commande->setNomClient('Nom Client Exemple');  // Remplacer par des valeurs dynamiques si nécessaire
    $commande->setAdresseEmail('client@example.com');  
    $commande->setAdresse('123 Rue Exemple');
    $commande->setStatutCom('en cours');
    $commande->setDateCommande(new \DateTime());

    $totalCommande = 0;

    foreach ($panier as $idProduit => $produitData) {
        $produit = $produitRepository->find($idProduit);
        if ($produit) {
            $commande->addProduit($produit);
            $totalCommande += $produit->getPrixProduit() * $produitData['quantiteProduit'];
        }
    }

    $commande->setTotalCom($totalCommande);

    // Enregistrement en base de données
    $entityManager->persist($commande);
    $entityManager->flush();

    // Vider le panier
    $session->remove('panier');

    // Message de succès
    $this->addFlash('success', 'Votre commande a été validée avec succès.');

    return $this->redirectToRoute('app_commande');
}

    

    // Afficher une commande spécifique
    #[Route('/commande/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/formshowcommande.html.twig', [
            'commande' => $commande,
        ]);
    }

    // Modifier une commande existante
    #[Route('/commande/{id}/edit', name: 'editCommande', methods: ['GET', 'POST'])]
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

    // Supprimer une commande
    #[Route('/{id}/delete', name: 'deleteCommande', methods: ['POST'])]
    public function delete(
        Request $request,
        Commande $commande,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $commande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();

            $this->addFlash('success', 'Commande supprimée avec succès.');
        }

        return $this->redirectToRoute('app_commande');
    }
}
