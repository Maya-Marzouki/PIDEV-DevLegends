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
use Knp\Component\Pager\PaginatorInterface;
use libphonenumber\PhoneNumberUtil;



class CentreController extends AbstractController
{
    #[Route('/admin/centre', name: 'app_centre_index')]
    public function index(CentreRepository $centreRepository): Response
    {
        $centres = $centreRepository->findAll();

        return $this->render('centre/formshow.html.twig', [
            'centres' => $centres,
        ]);
    }

    #[Route('/centreclient', name: 'centreclient')]
    public function showCentreClient(CentreRepository $centreRepository, PaginatorInterface $paginator, Request $request): Response
{
    $searchTerm = $request->query->get('search');
    $sortBy = $request->query->get('sortBy', 'id'); // Trier par ID par défaut (ordre d'ajout)
    $sortOrder = $request->query->get('sortOrder', 'ASC'); // Ordre croissant par défaut (du plus ancien au plus récent)

    // Vérifier si l'utilisateur demande un tri par nom A-Z ou Z-A
    if ($request->query->get('order') == 'ASC' || $request->query->get('order') == 'DESC') {
        $sortBy = 'nomCentre'; // On trie par nomCentre
        $sortOrder = $request->query->get('order'); // On récupère l'ordre
    }

    $query = $centreRepository->searchAndSort($searchTerm, $sortBy, $sortOrder);

    $centres = $paginator->paginate($query, $request->query->getInt('page', 1), 3);

    if ($request->isXmlHttpRequest()) {
        return $this->render('centre/_centres_list.html.twig', ['centres' => $centres]);
    }

    return $this->render('centre/showclient.html.twig', [
        'centres' => $centres,
        'order' => $sortOrder,
    ]);
}

    #[Route('/admin/addcentre', name: 'insertCentre', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $mr): Response
    {
        $centre = new Centre();
        $form = $this->createForm(CentreType::class, $centre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // La valeur de 'telCentre' est automatiquement récupérée grâce au binding entre le formulaire et l'entité
            $telCentre = $centre->getTelCentre();
            dump($telCentre);  // Affiche la valeur du téléphone dans le débogueur (profiler Symfony)
        
            // Traitement de l'image de la photo
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
        
            // Enregistrement du centre
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

    #[Route('/admin/centre/{id}/edit', name: 'editCentre', methods: ['GET', 'POST'])]
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


    #[Route('/admin/centre/{id}/delete', name: 'deleteCentre')]
    public function deleteCentre(ManagerRegistry $mr, CentreRepository $repo, $id): Response
    {
        $manager = $mr->getManager();
        $centre = $repo->find($id);
        $manager->remove($centre);
        $manager->flush();

        return $this->redirectToRoute("app_centre_index");
    }
}