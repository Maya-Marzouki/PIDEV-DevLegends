<?php

namespace App\Controller;

use App\Entity\Contrat;
use App\Form\ContratType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ContratRepository;

class ContratController extends AbstractController
{
    #[Route('/contrat', name: 'app_contrat_index')]
    public function index(ManagerRegistry $mr): Response
    {
        $contrats = $mr->getRepository(Contrat::class)->findAll();

        return $this->render('contrat/formshowcontrat.html.twig', [
            'contrats' => $contrats,
        ]);
    }

    #[Route('/contratclient', name: 'contratclient')]
    public function showcontratclient(ManagerRegistry $mr): Response
    {
        $contrats = $mr->getRepository(Contrat::class)->findAll();

        return $this->render('contrat/showclientcontrat.html.twig', [
            'contrats' => $contrats,
        ]);
    }


    #[Route('/addcontrat', name: 'insertContrat', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $mr): Response
    {
        $contrat = new Contrat();
        $form = $this->createForm(contratType::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $mr->getManager();
            $manager->persist($contrat);
            $manager->flush();

            return $this->redirectToRoute('contratclient');
        }

        return $this->render('contrat/formaddcontrat.html.twig', [
            'contrat' => $contrat,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/contrat/{id}', name: 'app_contrat_show', methods: ['GET'])]
    public function show(Contrat $contrat): Response
    {
        return $this->render('contrat/formshowcontrat.html.twig', [
            'contrat' => $contrat,
        ]);
    }

    // Décommentez la route pour l'édition
    #[Route('/contrat/{id}/edit', name: 'editContrat')]
    public function edit(Request $request, Contrat $contrat, ManagerRegistry $mr): Response
    {
        $form = $this->createForm(ContratType::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $mr->getManager();
            $manager->flush(); // Sauvegarde les modifications

            return $this->redirectToRoute('app_contrat_index');
        }

        return $this->render('contrat/formeditcontrat.html.twig', [
            'contrat' => $contrat,
            'form' => $form->createView(),
        ]);
    }


#[Route('/contrat/{id}/delete', name: 'deleteContrat')]
    public function deleteContrat(ManagerRegistry $mr, ContratRepository $repo, $id): Response
    {
        $manager = $mr->getManager();
        $contrat = $repo->find($id);
        $manager->remove($contrat);
        $manager->flush();

        return $this->redirectToRoute("app_contrat_index");
    }
}