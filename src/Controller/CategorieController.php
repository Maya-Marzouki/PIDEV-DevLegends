<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategorieRepository;

class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie_index')]
    public function index(ManagerRegistry $mr): Response
    {
        $categories = $mr->getRepository(Categorie::class)->findAll();

        return $this->render('categorie/formshowcategorie.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/categorieclient', name: 'categorieclient')]
    public function showcategorieclient(ManagerRegistry $mr): Response
    {
        $categories = $mr->getRepository(Categorie::class)->findAll();

        return $this->render('categorie/showclientcategorie.html.twig', [
            'categories' => $categories,
        ]);
    }


    #[Route('/addcategorie', name: 'insertCategorie', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $mr): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $mr->getManager();
            $manager->persist($categorie);
            $manager->flush();

            return $this->redirectToRoute('app_categorie_index');
        }

        return $this->render('categorie/formaddcategorie.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/categorie/{id}', name: 'app_categorie_show', methods: ['GET'])]
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/formshowcategorie.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    // Décommentez la route pour l'édition
    #[Route('/categorie/{id}/edit', name: 'editCategorie')]
    public function edit(Request $request, Categorie $categorie, ManagerRegistry $mr): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $mr->getManager();
            $manager->flush(); // Sauvegarde les modifications

            return $this->redirectToRoute('app_categorie_index');
        }

        return $this->render('categorie/formeditcategorie.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

#[Route('/categorie/{id}/delete', name: 'deleteCategorie')]
    public function deleteCategorie(ManagerRegistry $mr, CategorieRepository $repo, $id): Response
    {
        $manager = $mr->getManager();
        $categorie = $repo->find($id);
        $manager->remove($categorie);
        $manager->flush();

        return $this->redirectToRoute("app_categorie_index");
    }

    
}
