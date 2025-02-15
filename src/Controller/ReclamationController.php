<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReclamationRepository;

class ReclamationController extends AbstractController
{
    #[Route('/reclamation', name: 'app_reclamation_index')]
    public function index(ManagerRegistry $mr): Response
    {
        $reclamations = $mr->getRepository(Reclamation::class)->findAll();

        return $this->render('reclamation/formshowreclamation.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }

    #[Route('/reclamationclient', name: 'reclamationclient')]
    public function showreclamationclient(ManagerRegistry $mr): Response
    {
        $reclamations = $mr->getRepository(Reclamation::class)->findAll();

        return $this->render('reclamation/showclientreclamation.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }


    #[Route('/addreclamation', name: 'insertReclamation', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $mr): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $mr->getManager();
            $manager->persist($reclamation);
            $manager->flush();
            $this->addFlash('success', 'Votre réclamation a été envoyée avec succès.');

            return $this->redirectToRoute('reclamationclient');
        }

        return $this->render('reclamation/formaddreclamation.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reclamation/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/formshowreclamation.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    // Décommentez la route pour l'édition
    #[Route('/reclamation/{id}/edit', name: 'editReclamation')]
    public function edit(Request $request, Reclamation $reclamation, ManagerRegistry $mr): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $mr->getManager();
            $manager->flush(); // Sauvegarde les modifications
            $this->addFlash('success', 'La réclamation a été modifiée avec succès.');

            return $this->redirectToRoute('app_reclamation_index');
        }

        return $this->render('reclamation/formeditreclamation.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reclamation/{id}/delete', name: 'deleteReclamation')]
    public function deleteReclamation(ManagerRegistry $mr, ReclamationRepository $repo, $id): Response
    {
        $manager = $mr->getManager();
        $reclamation = $repo->find($id);
        $manager->remove($reclamation);
        $manager->flush();
        $this->addFlash('success', 'La réclamation a été supprimée avec succès.');

        return $this->redirectToRoute("app_reclamation_index");
    }
}
