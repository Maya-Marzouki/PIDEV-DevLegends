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
use Knp\Component\Pager\PaginatorInterface;


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
    public function showpackclient(ManagerRegistry $mr, PaginatorInterface $paginator, Request $request): Response
    {
        // Récupère les packs via une requête Doctrine
        $query = $mr->getRepository(Pack::class)->createQueryBuilder('p')->getQuery();

        // Pagination des packs (3 packs par page)
        $packs = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Page actuelle (1 par défaut)
            3 // Nombre d'éléments par page
        );

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

// src/Controller/PackController.php

#[Route('/pack/{id}/achat', name: 'achatPack')]
public function acheterPackAvecReduction(Request $request, Pack $pack): Response
{
    $codeReduction = $request->request->get('codeReduction');
    $discountPrice = $pack->getPrixPack(); // prix original

    // Vérifiez si le code de réduction est valide
    if ($codeReduction && $codeReduction === 'reduc10%') { // Code de réduction à valider
        $discountPrice = $discountPrice * 0.9; // Applique la réduction de 10%
        $this->addFlash('success', 'Code de réduction appliqué avec succès !');
    }

    return $this->render('pack/achat.html.twig', [
        'pack' => $pack,
        'codeReduction' => $codeReduction,
        'discountPrice' => $discountPrice, // Passer le prix réduit
    ]);
}

#[Route('/pack/{id}/paiement', name: 'paiementPack')]
public function paiement(int $id, ManagerRegistry $mr, Request $request): Response
{
    // Récupérer le pack
    $pack = $mr->getRepository(Pack::class)->find($id);

    // Récupérer le code de réduction depuis la requête
    $codeReduction = $request->request->get('codeReduction');
    $discountPrice = $pack->getPrixPack(); // Prix original

    // Appliquer la réduction si le code est valide
    if ($codeReduction && $codeReduction === 'reduc10%') {
        $discountPrice = $discountPrice * 0.9; // Réduction de 10%
    }

    // Passer les variables au template
    return $this->render('pack/paiement.html.twig', [
        'pack' => $pack,
        'discountPrice' => $discountPrice, // Passer le prix réduit
        'codeReduction' => $codeReduction, // Passer le code de réduction (optionnel)
    ]);
}

}


