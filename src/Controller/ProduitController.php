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
use Symfony\Component\HttpFoundation\JsonResponse;

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
    

    $form = $this->createForm(ProduitType::class, $produit);
    $form->handleRequest($request);
    
   if ($form->isSubmitted() && $form->isValid()) {
        // Mettre à jour le statut du produit en fonction de la quantité
       
        
        $produit->updateStatut();
    
        
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
             
            $produit->updateStatut();
    
            $entityManager->persist($produit);
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

    
 

    


     // Route pour afficher le panier
     #[Route('/panier', name: 'panier', methods: ['GET', 'POST'])]
public function panier(Request $request, RequestStack $requestStack): Response
{
    // Récupération de la session et du panier
    $session = $requestStack->getCurrentRequest()->getSession();
    $panier = $session->get('panier', []);

    // Calcul du total du panier
    $total = 0;
    foreach ($panier as $item) {
        $total += $item['prixProduit'] * $item['quantiteProduit'];
    }

    // Affichage du contenu du panier pour débogage
    dump($panier);  // Ajout de cette ligne pour afficher le contenu du panier

    return $this->render('produit/panier.html.twig', [
        'panier' => $panier,
        'total' => $total,
    ]);
}

 
     #[Route('/panier/ajouter', name: 'ajouter_au_panier', methods: ['GET', 'POST'])]
     public function ajouterAuPanier(Request $request, RequestStack $requestStack): Response
     {
        
   
        $idProduit = $request->request->get('idProduit');
        $nomProduit = $request->request->get('nomProduit');
        $prixProduit = $request->request->get('prixProduit');
        $quantiteProduit = $request->request->get('quantiteProduit');
    
        if (!$idProduit || !$nomProduit || !$prixProduit || !$quantiteProduit || $quantiteProduit <= 0) {
            $this->addFlash('error', 'Les données sont invalides, veuillez vérifier.');
            return $this->redirectToRoute('panier');
        }
    
        // Récupération de la session
        $session = $requestStack->getCurrentRequest()->getSession();
        $panier = $session->get('panier', []);
    
        // Si le produit existe déjà dans le panier, on augmente la quantité
        if (isset($panier[$idProduit])) {
            $panier[$idProduit]['quantiteProduit'] += $quantiteProduit;
        } else {
            // Sinon, on l'ajoute au panier avec son ID
            $panier[$idProduit] = [
                'idProduit' => $idProduit,
                'nomProduit' => $nomProduit,
                'prixProduit' => $prixProduit,
                'quantiteProduit' => $quantiteProduit,
                
            ];
        }
    
        // Mise à jour du panier dans la session
        $session->set('panier', $panier);
    
        $this->addFlash('success', 'Produit ajouté au panier avec succès !');
    
        return $this->redirectToRoute('panier');
    }
    #[Route('/panier/supprimer/{id}', name: 'supprimer_du_panier', methods: ['POST'])]
public function supprimerDuPanier(Request $request, RequestStack $requestStack, $id): Response
{
    // Vérification du token CSRF
    if ($this->isCsrfTokenValid('supprimer' . $id, $request->request->get('_token'))) {
        // Récupération de la session et du panier
        $session = $requestStack->getCurrentRequest()->getSession();
        $panier = $session->get('panier', []);

        // Suppression du produit du panier
        if (isset($panier[$id])) {
            unset($panier[$id]);
        }

        // Mise à jour du panier dans la session
        $session->set('panier', $panier);

        $this->addFlash('success', 'Produit supprimé du panier.');
    }

    return $this->redirectToRoute('panier');
}
#[Route('/panier/modifier/{id}', name: 'modifier_quantite', methods: ['POST'])]
public function modifierQuantite(Request $request, RequestStack $requestStack, $id): Response
{
    // Récupérer la nouvelle quantité
    $data = json_decode($request->getContent(), true);
    $quantiteProduit = $data['quantiteProduit'];

    if ($quantiteProduit <= 0) {
        return $this->json(['success' => false, 'message' => 'La quantité doit être supérieure à zéro.']);
    }

    // Récupération de la session et du panier
    $session = $requestStack->getCurrentRequest()->getSession();
    $panier = $session->get('panier', []);

    // Modifier la quantité du produit
    if (isset($panier[$id])) {
        $panier[$id]['quantiteProduit'] = $quantiteProduit;
    }

    // Mise à jour du panier dans la session
    $session->set('panier', $panier);

    // Calcul du total du panier
    $total = 0;
    foreach ($panier as $item) {
        $total += $item['prixProduit'] * $item['quantiteProduit'];
    }

    return $this->json(['success' => true, 'total' => $total]);
}


 }


