<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitRepository;

class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit_index')]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/formshowproduit.html.twig', [
            'produits' => $produitRepository->findAll(),
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
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index');
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

            return $this->redirectToRoute('app_produit_index');
        }

        return $this->render('produit/formeditproduit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/produit/{id}/delete', name: 'deleteProduit', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        
        if (!$this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide, suppression annulée.');
            return $this->redirectToRoute('app_produit_index');
        }
    
        
        $entityManager->remove($produit);
        $entityManager->flush();
    
       
        $this->addFlash('success', 'Produit supprimé avec succès.');
    
       
        return $this->redirectToRoute('app_produit_index');
    }
    
}