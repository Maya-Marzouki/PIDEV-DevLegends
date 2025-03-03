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
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;


class PackController extends AbstractController
{
    #[Route('/admin/pack', name: 'app_pack_index')]
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


    #[Route('/admin/addpack', name: 'insertPack', methods: ['GET', 'POST'])]
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
    #[Route('/admin/pack/{id}/edit', name: 'editPack')]
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


#[Route('/admin/pack/{id}/delete', name: 'deletePack')]
    public function deletePack(ManagerRegistry $mr, PackRepository $repo, $id): Response
    {
        $manager = $mr->getManager();
        $pack = $repo->find($id);
        $manager->remove($pack);
        $manager->flush();

        return $this->redirectToRoute("app_pack_index");
    }

    #[Route('/profil/pack/{id}/achat', name: 'achatPack')]
public function acheterPack(Pack $pack): Response
{
    return $this->render('pack/achat.html.twig', [
        'pack' => $pack,
    ]);
}

// src/Controller/PackController.php

#[Route('/profil/pack/{id}/achat', name: 'achatPack')]
public function acheterPackAvecReduction(Request $request, Pack $pack, ManagerRegistry $mr): Response
{
    $codeReduction = $request->request->get('codeReduction');
    $discountPrice = $pack->getPrixPack(); // Prix original

    // Vérifiez si le code de réduction est valide et non utilisé
    if ($codeReduction && $codeReduction === $pack->getDiscountCode()) {
        if ($pack->isUsed()) {
            $this->addFlash('error', 'Ce code de réduction a déjà été utilisé.');
        } else {
            $discountPrice = $discountPrice * 0.9; // Applique la réduction de 10%
            $pack->setIsUsed(true); // Marquer le code comme utilisé
            $mr->getManager()->flush(); // Enregistrer les modifications
            $this->addFlash('success', 'Code de réduction appliqué avec succès !');
        }
    } elseif ($codeReduction) {
        $this->addFlash('error', 'Code de réduction invalide.');
    }

    return $this->render('pack/achat.html.twig', [
        'pack' => $pack,
        'codeReduction' => $codeReduction,
        'discountPrice' => $discountPrice, // Passer le prix réduit
    ]);
}


// src/Controller/PackController.php

private function generateDiscountCode(): string
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 8; $i++) { // Longueur du code : 8 caractères
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

#[Route('/profil/pack/{id}/paiement', name: 'paiementPack')]
public function paiement(int $id, ManagerRegistry $mr, Request $request): Response
{
    // Récupérer le pack
    $pack = $mr->getRepository(Pack::class)->find($id);

    // Récupérer le code de réduction depuis la requête
    $codeReduction = $request->request->get('codeReduction');
    $discountPrice = $pack->getPrixPack(); // Prix original

    // Appliquer la réduction si le code est valide et non utilisé
    if ($codeReduction && $codeReduction === $pack->getDiscountCode() && !$pack->isUsed()) {
        $discountPrice = $discountPrice * 0.9; // Réduction de 10%
        $pack->setIsUsed(true); // Marquer le code comme utilisé
        $mr->getManager()->flush(); // Enregistrer les modifications
    }

    // Passer les variables au template
    return $this->render('pack/paiement.html.twig', [
        'pack' => $pack,
        'discountPrice' => $discountPrice, // Passer le prix réduit
        'codeReduction' => $codeReduction, // Passer le code de réduction (optionnel)
    ]);
}


#[Route('/profil/pack/{id}/generate-discount', name: 'generate_discount', methods: ['POST'])]
public function generateDiscountCodeAction(ManagerRegistry $mr, Pack $pack): JsonResponse
{
    $entityManager = $mr->getManager();

    // Vérifier si un code de réduction existe déjà pour ce pack
    if ($pack->getDiscountCode()) {
        return new JsonResponse([
            'success' => true,
            'discountCode' => $pack->getDiscountCode(), // Renvoyer le code existant
            'message' => 'Code de réduction déjà généré.',
        ]);
    }

    // Générer un nouveau code unique
    $unique = false;
    $discountCode = '';

    while (!$unique) {
        $discountCode = $this->generateDiscountCode();
        $existingPack = $entityManager->getRepository(Pack::class)->findOneBy(['discountCode' => $discountCode]);
        if (!$existingPack) {
            $unique = true;
        }
    }

    // Enregistrer le nouveau code dans le pack
    $pack->setDiscountCode($discountCode);
    $entityManager->flush();

    return new JsonResponse([
        'success' => true,
        'discountCode' => $discountCode, // Renvoyer le nouveau code
        'message' => 'Code de réduction généré avec succès.',
    ]);
}
#[Route('/profil/pack/{id}/reset-discount', name: 'reset_discount', methods: ['POST'])]
public function resetDiscountCodeAction(ManagerRegistry $mr, Pack $pack): JsonResponse
{
    $entityManager = $mr->getManager();

    // Générer un nouveau code unique
    $unique = false;
    $discountCode = '';

    while (!$unique) {
        $discountCode = $this->generateDiscountCode();
        $existingPack = $entityManager->getRepository(Pack::class)->findOneBy(['discountCode' => $discountCode]);
        if (!$existingPack) {
            $unique = true;
        }
    }

    // Enregistrer le nouveau code dans le pack
    $pack->setDiscountCode($discountCode);
    $entityManager->flush();

    return new JsonResponse([
        'success' => true,
        'discountCode' => $discountCode, // Renvoyer le nouveau code
        'message' => 'Nouveau code de réduction généré avec succès.',
    ]);
}

}
