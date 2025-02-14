<?php

namespace App\Controller;

use App\Entity\Centre;
use App\Form\CentreType;
use App\Repository\CentreRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CentreController extends AbstractController
{
    #[Route('/centre', name: 'app_centre_index')]
    public function index(CentreRepository $centreRepository): Response
    {
        $centres = $centreRepository->findAll();

        return $this->render('centre/formshow.html.twig', [
            'centres' => $centres,
        ]);
    }

    #[Route('/centreclient', name: 'centreclient')]
    public function showCentreClient(CentreRepository $centreRepository): Response
    {
        $centres = $centreRepository->findAll();

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
            $photoFile = $form->get('photoCentre')->getData();

            if ($photoFile) {
                $newFilename = uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/assets/images/',
                        $newFilename
                    );
                    $centre->setPhotoCentre('assets/images/' . $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                }
            }

            $manager = $mr->getManager();
            $manager->persist($centre);
            $manager->flush();

            $this->addFlash('success', 'Centre ajouté avec succès !');
            return $this->redirectToRoute('app_centre_index');
        }

        return $this->render('centre/formaddcentre.html.twig', [
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

    #[Route('/centre/{id}/edit', name: 'editCentre', methods: ['GET', 'POST'])]
    public function edit(Request $request, Centre $centre, ManagerRegistry $mr): Response
    {
        $form = $this->createForm(CentreType::class, $centre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photoCentre')->getData();

            if ($photoFile) {
                $newFilename = uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/assets/images/',
                        $newFilename
                    );
                    $centre->setPhotoCentre('assets/images/' . $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                }
            }

            $mr->getManager()->flush();
            $this->addFlash('success', 'Centre mis à jour avec succès !');

            return $this->redirectToRoute('app_centre_index');
        }

        return $this->render('centre/formedit.html.twig', [
            'form' => $form->createView(),
            'centre' => $centre, // Ajout de la variable centre
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
