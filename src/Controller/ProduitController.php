<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Categorie;
use App\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Knp\Component\Pager\PaginatorInterface;


final class ProduitController extends AbstractController
{ 
    private $requestStack;
    #[Route('/produit', name: 'app_produit')]
    public function index(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();  // Récupère tous les produits
        return $this->render('produit/formshowproduit.html.twig', [
            'produits' => $produits,  // Passe les produits à la vue
        ]);
    }

    #[Route('/produitclient', name: 'produitclient')]
    public function showproduitclient(ProduitRepository $produitRepository, CategorieRepository $categorieRepository, Request $request, PaginatorInterface $paginator): Response
      {  $searchTerm = $request->query->get('search', '');
        $selectedCategorie = $request->query->get('categorie', ''); // Récupérer la catégorie sélectionnée
        
        // Récupération des données via le Repository
        $categories = $categorieRepository->findAll();
        $produits = $produitRepository->findBySearch($searchTerm, $selectedCategorie);
         
    // Récupération des produits
    $query = $produitRepository->findBySearchQuery($searchTerm, $selectedCategorie);
    // Pagination des produits
    $produits = $paginator->paginate(
        $query, // Requête
        $request->query->getInt('page', 1), // Page actuelle
        2// Nombre d'éléments par page
        
    );
        return $this->render('produit/showclientproduit.html.twig', [
            'produits' => $produits,
            'categories' => $categories,
            'searchTerm' => $searchTerm,
            'selectedCategorie' => $selectedCategorie,
        ]);
    }

    #[Route('/addproduit', name: 'insertProduit', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $produit = new Produit();
    

    $form = $this->createForm(ProduitType::class, $produit);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        $produit->setQteProduit($produit->getQteProduit()); // Mise à jour pour s'assurer que le statut change
        
    
        
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

 
#[Route('/ajouter-au-panier', name: 'ajouter_au_panier', methods: ['POST'])]
public function ajouterAuPanier(Request $request, SessionInterface $session, ProduitRepository $produitRepository, EntityManagerInterface $entityManager): JsonResponse
{
    $id = $request->request->get('idProduit');
    $quantite = (int) $request->request->get('quantiteProduit', 1);
    
    // Vérifier si le produit existe
    $produit = $produitRepository->find($id);
    if (!$produit) {
        return new JsonResponse(['success' => false, 'message' => 'Produit introuvable.'], 404);
    }
   
    // Vérifier si la quantité demandée est disponible
    if ($quantite > $produit->getQteProduit()) {
        return new JsonResponse([
            'success' => false,
            'message' => 'Stock insuffisant. Quantité disponible : ' . $produit->getQteProduit()
        ], 400);
    }
    

    // Récupérer le panier de la session
    $panier = $session->get('panier', []);

    // Ajouter ou mettre à jour la quantité du produit dans le panier
    if (isset($panier[$id])) {
        $panier[$id]['quantiteProduit'] += $quantite;
    } else {
        $panier[$id] = [
            'nomProduit' => $produit->getNomProduit(),
            'prixProduit' => $produit->getPrixProduit(),
            'quantiteProduit' => $quantite
        ];
    }

    $session->set('panier', $panier);

    // Mise à jour du stock en base de données
    $nouveauStock = max($produit->getQteProduit() - $quantite, 0);
    $produit->setQteProduit($nouveauStock);
    $produit->updateStatut(); // Vérifie et met à jour le statut
    $entityManager->persist($produit);
    $entityManager->flush();

    return new JsonResponse([
        'success' => true,
        'message' => 'Produit ajouté au panier.',
        'stock' => $nouveauStock
    ]);
}

    #[Route('/supprimer-du-panier/{id}', name: 'supprimer_du_panier', methods: ['POST'])]
public function supprimerDuPanier(int $id, SessionInterface $session, ProduitRepository $produitRepository, EntityManagerInterface $entityManager): JsonResponse
{
    $panier = $session->get('panier', []);

    if (!isset($panier[$id])) {
        return new JsonResponse(['success' => false, 'message' => 'Produit non trouvé dans le panier'], 400);
    }

    $quantiteAjoutee = $panier[$id]['quantiteProduit'];
    unset($panier[$id]);
    $session->set('panier', $panier);

    // Restaurer la quantité dans la base de données
    $produit = $produitRepository->find($id);
    if ($produit) {
        $produit->setQteProduit($produit->getQteProduit() + $quantiteAjoutee);
        $produit->updateStatut(); // Vérifie et met à jour le statut
        $entityManager->persist($produit);
        $entityManager->flush();
    }

    return new JsonResponse(['success' => true, 'stock' => $produit->getQteProduit()]);
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


