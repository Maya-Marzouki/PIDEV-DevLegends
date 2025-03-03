<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReclamationRepository;
use App\Repository\UserRepository;
use App\Service\EmailService;
use Knp\Component\Pager\PaginatorInterface;

class ReclamationController extends AbstractController
{
    #[Route('/reclamation', name: 'app_reclamation_index')]
    public function index(ReclamationRepository $reclamRepo, PaginatorInterface $paginator, Request $request): Response
    {
        $search = $request->query->get('search', '');
        $queryBuilder = $reclamRepo->searchReclamation($search); // Maintenant, c'est un QueryBuilder

        // Pagination
        $reclamations = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            5 // Nombre d'éléments par page
        );

        return $this->render('reclamation/formshowreclamation.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }



    #[Route('/reclamationclient', name: 'reclamationclient')]
    public function showreclamationclient(ManagerRegistry $mr, PaginatorInterface $paginator, Request $request): Response
    {
        // Récupérer toutes les réclamations
        $reclamationsQuery = $mr->getRepository(Reclamation::class)->findAll();

        // Pagination
        $reclamations = $paginator->paginate(
            $reclamationsQuery, // Requête à paginer
            $request->query->getInt('page', 1), // Numéro de la page, 1 par défaut
            6 // Nombre d'éléments par page
        );

        return $this->render('reclamation/showclientreclamation.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }


    #[Route('/addreclamation', name: 'insertReclamation', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $mr, UserRepository $userRepo): Response
    {
        $reclamation = new Reclamation();
        $reclamation->setStatutRec('Pas traitée'); // Initialisation du statut

        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $mr->getManager();

            // Vérifier si l'email correspond à un utilisateur existant
            $email = $reclamation->getEmailDes();
            $user = $userRepo->findOneBy(['userEmail' => $email]);

            if ($user) {
                $reclamation->setUser($user); // Associer l'utilisateur à la réclamation
            }

            $manager->persist($reclamation);
            $manager->flush();

            $this->addFlash('success', 'Votre réclamation a été envoyée avec succès.');

            return $this->redirectToRoute('reclamationclient');
        }

        return $this->render('reclamation/formaddreclamation.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/reclamation/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/formshowreclamation.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    // Décommentez la route pour l'édition
    #[Route('/reclamation/{id}/edit', name: 'editReclamation')]
    public function edit(Request $request, Reclamation $reclamation, ManagerRegistry $mr): Response
    {
        // Vérifier si l'utilisateur connecté est le propriétaire de la réclamation
        if ($this->getUser() !== $reclamation->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier cette réclamation.');
        }

        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $mr->getManager();
            $manager->flush(); // Sauvegarde les modifications
            $this->addFlash('success', 'La réclamation a été modifiée avec succès.');

            return $this->redirectToRoute('reclamationclient');
        }

        return $this->render('reclamation/formeditreclamation.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reclamation/{id}/delete', name: 'deleteReclamation')]
    public function deleteReclamation(ManagerRegistry $mr, ReclamationRepository $repo, $id): Response
    {
        $reclamation = $repo->find($id);

        // Vérifier si l'utilisateur connecté est le propriétaire de la réclamation
        if ($this->getUser() !== $reclamation->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à supprimer cette réclamation.');
        }

        $manager = $mr->getManager();
        $manager->remove($reclamation);
        $manager->flush();
        $this->addFlash('success', 'La réclamation a été supprimée avec succès.');

        return $this->redirectToRoute("reclamationclient");
    }

    #[Route('/reclamation/{id}/traiter', name: 'traiterReclamation')]
    public function traiterReclamation(Reclamation $reclamation, ManagerRegistry $mr, EmailService $emailService): Response
    {
        if ($reclamation->getStatutRec() === 'Pas traitée') {
            $reclamation->setStatutRec('Traitée');
            $manager = $mr->getManager();
            $manager->flush();

            // Envoi de l'email de confirmation
            $emailService->sendReclamationConfirmation($reclamation->getEmailDes());

            $this->addFlash('success', 'La réclamation a été traitée avec succès et un email de confirmation a été envoyé.');
        } else {
            $this->addFlash('warning', 'Cette réclamation est déjà traitée.');
        }

        return $this->redirectToRoute('app_reclamation_index');
    }
}
