<?php

namespace App\Controller;

use App\Entity\Centre;
use App\Form\CentreType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CentreRepository;

class CentreController extends AbstractController
{
    #[Route('/centre', name: 'app_centre_index')]
    public function index(ManagerRegistry $mr): Response
    {
        $centres = $mr->getRepository(Centre::class)->findAll();

        return $this->render('centre/formshow.html.twig', [
            'centres' => $centres,
        ]);
    }

    #[Route('/centreclient', name: 'centreclient')]
    public function showcentreclient(ManagerRegistry $mr): Response
    {
        $centres = $mr->getRepository(Centre::class)->findAll();

        return $this->render('centre/showclient.html.twig', [
            'centres' => $centres,
        ]);
    }


   

    #[Route('/addcentre', name: 'insertCentre', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $mr): Response
    {
        $centre = new Centre();
        $form = $this->createForm(CentreType::class, $centre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $mr->getManager();
            $manager->persist($centre);
            $manager->flush();

            return $this->redirectToRoute('app_centre_index');
        }

        return $this->render('centre/formaddcentre.html.twig', [
            'centre' => $centre,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/centre/{id}', name: 'app_centre_show', methods: ['GET'])]
    public function show(Centre $centre): Response
    {
        return $this->render('centre/formshow.html.twig', [
            'centre' => $centre,
        ]);
    }

    // Décommentez la route pour l'édition
    #[Route('/centre/{id}/edit', name: 'editCentre')]
    public function edit(Request $request, Centre $centre, ManagerRegistry $mr): Response
    {
        $form = $this->createForm(CentreType::class, $centre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $mr->getManager();
            $manager->flush(); // Sauvegarde les modifications

            return $this->redirectToRoute('app_centre_index');
        }

        return $this->render('centre/formedit.html.twig', [
            'centre' => $centre,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/centre/{id}/delete', name: 'deleteCentre')]
    public function deleteCentre(ManagerRegistry $mr, CentreRepository $repo, $id): Response
    {
        $manager = $mr->getManager();
        $centre = $repo->find($id);
        $manager->remove($centre);
        $manager->flush();

        return $this->redirectToRoute("app_centre_index");
    }


    
}
