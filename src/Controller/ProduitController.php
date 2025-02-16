<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\RequestStack;

final class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();  // Récupère tous les produits
        return $this->render('produit/formshowproduit.html.twig', [
            'produits' => $produits,  // Passe les produits à la vue
        ]);
    }

    #[Route('/produitclient', name: 'produitclient')]
    public function showproduitclient(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/showclientproduit.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/addproduit', name: 'insertProduit', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $produit = new Produit();
    // Définir le statut par défaut à 'Indisponible'
    $produit->setStatutProduit(false);  // Indisponible par défaut

    $form = $this->createForm(ProduitType::class, $produit);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Vérifier la quantité et ajuster le statut
        if ($produit->getQteProduit() > 0) {
            $produit->setStatutProduit(true);  // Disponible si quantité > 0
        } else {
            $produit->setStatutProduit(false);  // Indisponible si quantité = 0
        }

        $entityManager->persist($produit);
        $entityManager->flush();

        return $this->redirectToRoute('app_produit');
    }

    return $this->render('produit/formaddproduit.html.twig', [
        'form' => $form->createView(),
    ]);
}



    #[Route('/produit/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/formshowproduit.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/produit/{id}/edit', name: 'editProduit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_produit');
        }

        return $this->render('produit/formeditproduit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/produit/{id}/delete', name: 'deleteProduit', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();

            $this->addFlash('success', 'Produit supprimé avec succès.');
        }

        return $this->redirectToRoute('app_produit');
    }

    
 

    


    // Route pour ajouter un produit au panier
    #[Route('/panier', name: 'panier', methods: ['POST'])]
    public function ajouterAuPanier(Request $request, RequestStack $requestStack): Response
    {
        $idProduit = $request->request->get('idProduit');
        $nomProduit = $request->request->get('nomProduit');
        $prixProduit = $request->request->get('prixProduit');
        $quantiteProduit = $request->request->get('quantiteProduit');

        // Vérification des données envoyées
        if (!$idProduit || !$nomProduit || !$prixProduit || !$quantiteProduit || $quantiteProduit <= 0) {
            // Ajout d'un message d'erreur si les données sont invalides
            $this->addFlash('error', 'Les données sont invalides, veuillez vérifier.');
            return $this->redirectToRoute('panier');
        }

        // Récupération de la session et du panier
        $session = $requestStack->getCurrentRequest()->getSession();
        $panier = $session->get('panier', []);

        // Si le produit existe déjà dans le panier, on augmente la quantité
        if (isset($panier[$idProduit])) {
            $panier[$idProduit]['quantiteProduit'] += $quantiteProduit;
        } else {
            // Sinon, on l'ajoute au panier avec sa quantité
            $panier[$idProduit] = [
                'nomProduit' => $nomProduit,
                'prixProduit' => $prixProduit,
                'quantiteProduit' => $quantiteProduit,
            ];
        }

        // Mise à jour du panier dans la session
        $session->set('panier', $panier);

        // Ajout d'un message de succès
        $this->addFlash('success', 'Produit ajouté au panier avec succès !');

        // Redirection vers la page du panier
        return $this->redirectToRoute('panier');
    }

    // Route pour afficher le contenu du panier
    #[Route('/panier', name: 'panier')]
    public function panier(RequestStack $requestStack): Response
    {
        // Récupération de la session et du panier
        $session = $requestStack->getCurrentRequest()->getSession();
        $panier = $session->get('panier', []);

        // Calcul du total du panier
        $total = 0;
        foreach ($panier as $item) {
            $total += $item['prixProduit'] * $item['quantiteProduit'];
        }

        // Rendu de la page du panier avec les produits et le total
        return $this->render('produit/panier.html.twig', [
            'panier' => $panier,
            'total' => $total,
        ]);
    }
}

