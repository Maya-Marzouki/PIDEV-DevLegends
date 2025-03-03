<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EvenementRepository;

class EvenementController extends AbstractController
{
    #[Route('/evenement', name: 'app_evenement_index')]
    public function index(ManagerRegistry $mr): Response
    {
        $evenements = $mr->getRepository(Evenement::class)->findAll();

        return $this->render('evenement/formshowevenement.html.twig', [
            'evenements' => $evenements,
        ]);
    }

    #[Route('/evenementclient', name: 'evenementclient')]
    public function showevenementclient(ManagerRegistry $mr): Response
    {
        $evenements = $mr->getRepository(Evenement::class)->findAll();

        return $this->render('evenement/showclientevenement.html.twig', [
            'evenements' => $evenements,
        ]);
    }

    #[Route('/addevenement', name: 'insertevenement', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $mr): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $mr->getManager();
            // Lors de la création, la formation est automatiquement liée à l'événement via le formulaire
            $manager->persist($evenement);
            $manager->flush();

            return $this->redirectToRoute('app_evenement_index');
        }

        return $this->render('evenement/formaddevevenement.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/evenement/{id}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/formshowevenement.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    // Décommentez la route pour l'édition
    #[Route('/evenement/{id}/edit', name: 'editEvenement')]
    public function edit(Request $request, Evenement $evenement, ManagerRegistry $mr): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $mr->getManager();
            // Lors de l'édition, la formation sélectionnée est également mise à jour dans l'événement
            $manager->flush(); // Sauvegarde les modifications

            return $this->redirectToRoute('app_evenement_index');
        }

        return $this->render('evenement/formeditevenement.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/evenement/{id}/delete', name: 'deleteEvenement')]
    public function deleteEvenement(ManagerRegistry $mr, EvenementRepository $repo, $id): Response
    {
        $manager = $mr->getManager();
        $evenements = $repo->find($id);
        $manager->remove($evenements);
        $manager->flush();
        
        $this->addFlash('success', 'L\'événement a été supprimé avec succès.');

        return $this->redirectToRoute("app_evenement_index");
    }
}
