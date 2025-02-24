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
    #[Route('/centre', name: 'app_centre_index')]
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
        // Récupérer le terme de recherche depuis la requête
        $searchTerm = $request->query->get('search');
        
        // Récupérer l'ordre de tri depuis la requête (par défaut, pas de tri)
        $order = $request->query->get('order');
    
        // Créer une requête Doctrine de base
        $queryBuilder = $centreRepository->createQueryBuilder('c');
    
        // Appliquer le filtre de recherche si un terme de recherche est présent
        if ($searchTerm) {
            $queryBuilder->andWhere('c.nomCentre LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }
    
        // Appliquer le tri uniquement si un ordre est spécifié
        if ($order === 'asc') {
            $queryBuilder->orderBy('c.nomCentre', 'ASC');
        } elseif ($order === 'desc') {
            $queryBuilder->orderBy('c.nomCentre', 'DESC');
        }
    
        // Paginer les résultats (3 centres par page)
        $centres = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1), // Page actuelle (1 par défaut)
            3 // Nombre d'éléments par page
        );
    
        return $this->render('centre/showclient.html.twig', [
            'centres' => $centres,
            'order' => $order, // Passer l'ordre de tri à la vue
        ]);
    }

    #[Route('/addcentre', name: 'insertCentre', methods: ['GET', 'POST'])]
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
