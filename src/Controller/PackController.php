<?php

namespace App\Controller;

use App\Entity\Pack;
use App\Form\PackType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PackRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class PackController extends AbstractController
{
    #[Route('/pack', name: 'app_pack_index')]
    public function index(ManagerRegistry $mr): Response
    {
        $packs = $mr->getRepository(Pack::class)->findAll();

        return $this->render('pack/formshowpack.html.twig', [
            'packs' => $packs,
        ]);
    }

    #[Route('/packclient', name: 'packclient')]
    public function showpackclient(ManagerRegistry $mr): Response
    {
        $packs = $mr->getRepository(Pack::class)->findAll();

        return $this->render('pack/showclientpack.html.twig', [
            'packs' => $packs,
        ]);
    }


    #[Route('/addpack', name: 'insertPack', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $mr): Response
    {
        $pack = new Pack();
        $form = $this->createForm(PackType::class, $pack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photoPack')->getData();

            if ($photoFile) {
                $newFilename = uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/assets/images/',
                        $newFilename
                    );
                    $pack->setPhotoPack('assets/images/' . $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                }
            }

            $manager = $mr->getManager();
            $manager->persist($pack);
            $manager->flush();

            $this->addFlash('success', 'Pack ajouté avec succès !');
            return $this->redirectToRoute('app_pack_index');
        }

        return $this->render('pack/formaddpack.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/pack/{id}', name: 'app_pack_show', methods: ['GET'])]
    public function show(Pack $pack): Response
    {
        return $this->render('pack/formshowpack.html.twig', [
            'pack' => $pack,
        ]);
    }

    // Décommentez la route pour l'édition
    #[Route('/pack/{id}/edit', name: 'editPack')]
    public function edit(Request $request, Pack $pack, ManagerRegistry $mr): Response
    {
        $form = $this->createForm(PackType::class, $pack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photoPack')->getData();

            if ($photoFile) {
                $newFilename = uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/assets/images/',
                        $newFilename
                    );
                    $pack->setPhotoPack('assets/images/' . $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                }
            }

            $mr->getManager()->flush();
            $this->addFlash('success', 'Pack mis à jour avec succès !');

            return $this->redirectToRoute('app_pack_index');
        }

        return $this->render('pack/formeditpack.html.twig', [
            'form' => $form->createView(),
            'pack' => $pack, // Ajout de la variable pack
        ]);
    }


#[Route('/pack/{id}/delete', name: 'deletePack')]
    public function deletePack(ManagerRegistry $mr, PackRepository $repo, $id): Response
    {
        $manager = $mr->getManager();
        $pack = $repo->find($id);
        $manager->remove($pack);
        $manager->flush();

        return $this->redirectToRoute("app_pack_index");
    }

    #[Route('/pack/{id}/achat', name: 'achatPack')]
public function acheterPack(Pack $pack): Response
{
    return $this->render('pack/achat.html.twig', [
        'pack' => $pack,
    ]);
}

    #[Route('/pack/{id}/paiement', name: 'paiementPack')]
    public function paiement(int $id): Response
    {
        // Logique pour afficher la page de paiement
        return $this->render('pack/achat.html.twig', [
            'packId' => $id
        ]);
    }
}