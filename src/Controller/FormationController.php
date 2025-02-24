<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FormationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class FormationController extends AbstractController
{
    #[Route('/formation', name: 'app_formation_index')]
    public function index(ManagerRegistry $mr): Response
    {
        $formations = $mr->getRepository(Formation::class)->findAll();

        return $this->render('formation/formshowformation.html.twig', [
            'formations' => $formations,
        ]);
    }

    #[Route('/formationclient', name: 'formationclient')]
    public function showformationclient(ManagerRegistry $mr): Response
    {
        $formations = $mr->getRepository(Formation::class)->findAll();

        return $this->render('formation/showclientformation.html.twig', [
            'formations' => $formations,
        ]);
    }

    #[Route('/addformation', name: 'insertformation', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $mr): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $mr->getManager();
            $manager->persist($formation);
            $manager->flush();

            return $this->redirectToRoute('app_formation_index');
        }

        return $this->render('formation/formaddformation.html.twig', [
            'formationt' => $formation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/formation/{id}', name: 'app_formation_show', methods: ['GET'])]
    public function show(Formation $formation): Response
    {
        return $this->render('formation/formshowformation.html.twig', [
            'formation' => $formation,
        ]);
    }

    #[Route('/formation/{id}/edit', name: 'editFormation')]
    public function edit(Request $request, Formation $formation, ManagerRegistry $mr): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $mr->getManager();
            $manager->flush();

            return $this->redirectToRoute('app_formation_index');
        }

        return $this->render('formation/formeditformation.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/formation/{id}/delete', name: 'deleteFormation', methods: ['POST', 'DELETE'])]
    public function deleteFormation(ManagerRegistry $mr, FormationRepository $repo, $id): Response
    {
        $manager = $mr->getManager();
        $formation = $repo->find($id);

        if (!$formation) {
            throw $this->createNotFoundException('Formation non trouvée.');
        }

        $manager->remove($formation);
        $manager->flush();

        return $this->redirectToRoute("app_formation_index");
    }

    #[Route('/calendar/events', name: 'calendar_events', methods: ['GET'])]
    public function getEvents(ManagerRegistry $mr): JsonResponse
    {
        $formations = $mr->getRepository(Formation::class)->findAll();
        $events = [];

        foreach ($formations as $formation) {
            $events[] = [
                'title Formation' => $formation->getTitreFor(),
                'Date Formation' => $formation->getDateFor()->format('Y-m-d H:i:s'),
                'Lieu Formation' => $formation->getLieuFor(),
                'Statut' => $formation->getStatutFor(),
            ];
        }

        return new JsonResponse($events);
    }

    #[Route('/calendar', name: 'calendar_view')]
    public function calendar(): Response
    {
        return $this->render('formation/calendar.html.twig');
    }
}
