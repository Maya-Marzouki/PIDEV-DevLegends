<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FormationRepository;

class FormationController extends AbstractController
{
    #[Route('/formation', name: 'app_formation_index')]
    public function index(ManagerRegistry $mr): Response
    {
        $formations = $mr->getRepository(Formation::class)->findAll();

        return $this->render('formation/formshowformation.html.twig', [
            'formations' => $formations,
        ]);
    }

    #[Route('/formationclient', name: 'formationclient')]
    public function showformationclient(ManagerRegistry $mr): Response
    {
        $formations = $mr->getRepository(Formation::class)->findAll();

        return $this->render('formation/showclientformation.html.twig', [
            'formations' => $formations,
        ]);
    }


    #[Route('/addformation', name: 'insertformation', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $mr): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $mr->getManager();
            $manager->persist($formation);
            $manager->flush();

            return $this->redirectToRoute('app_formation_index');
        }

        return $this->render('formation/formaddformation.html.twig', [
            'formationt' => $formation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/formation/{id}', name: 'app_formation_show', methods: ['GET'])]
    public function show(Formation $formation): Response
    {
        return $this->render('formation/formshowformation.html.twig', [
            'formation' => $formation,
        ]);
    }

    // Décommentez la route pour l'édition
    #[Route('/formation/{id}/edit', name: 'editFormation')]
    public function edit(Request $request, Formation $formation, ManagerRegistry $mr): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $mr->getManager();
            $manager->flush(); // Sauvegarde les modifications

            return $this->redirectToRoute('app_formation_index');
        }

        return $this->render('formation/formeditformation.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }

#[Route('/formation/{id}/delete', name: 'deleteFormation')]
    public function deleteFormation(ManagerRegistry $mr, FormationRepository $repo, $id): Response
    {
        $manager = $mr->getManager();
        $formations = $repo->find($id);
        $manager->remove($formations);
        $manager->flush();

        return $this->redirectToRoute("app_formation_index");
    }

    
}