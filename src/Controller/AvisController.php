<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AvisRepository;

class AvisController extends AbstractController
{
    #[Route('/avis', name: 'app_avis_index')]
    public function index(ManagerRegistry $mr): Response
    {
        $aviss = $mr->getRepository(Avis::class)->findAll();

        return $this->render('avis/formshowavis.html.twig', [
            'aviss' => $aviss,
        ]);
    }

    #[Route('/avisclient', name: 'avisclient')]
    public function showavisclient(ManagerRegistry $mr): Response
    {
        $aviss = $mr->getRepository(Avis::class)->findAll();

        return $this->render('avis/showclientavis.html.twig', [
            'aviss' => $aviss,
        ]);
    }


    #[Route('/addavis', name: 'insertAvis', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $mr): Response
    {
        $avis = new Avis();
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $mr->getManager();
            $manager->persist($avis);
            $manager->flush();
            $this->addFlash('success', 'Votre avis a été envoyé avec succès.');

            return $this->redirectToRoute('avisclient');
        }

        return $this->render('avis/formaddavis.html.twig', [
            'avis' => $avis,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/avis/{id}', name: 'app_avis_show', methods: ['GET'])]
    public function show(Avis $avis): Response
    {
        return $this->render('avis/formshowavis.html.twig', [
            'avis' => $avis,
        ]);
    }

    // Décommentez la route pour l'édition
    #[Route('/avis/{id}/edit', name: 'editAvis')]
    public function edit(Request $request, Avis $avis, ManagerRegistry $mr): Response
    {
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $mr->getManager();
            $manager->flush(); // Sauvegarde les modifications
            $this->addFlash('success', 'L\'avis a été modifié avec succès.');

            return $this->redirectToRoute('app_avis_index');
        }

        return $this->render('avis/formeditavis.html.twig', [
            'avis' => $avis,
            'form' => $form->createView(),
        ]);
    }

#[Route('/avis/{id}/delete', name: 'deleteAvis')]
    public function deleteAvis(ManagerRegistry $mr, AvisRepository $repo, $id): Response
    {
        $manager = $mr->getManager();
        $avis = $repo->find($id);
        $manager->remove($avis);
        $manager->flush();
        $this->addFlash('success', 'L\'avis a été supprimé avec succès.');

        return $this->redirectToRoute("app_avis_index");
    }

    
}