<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use Doctrine\Persistence\ManagerRegistry;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FormationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Logo\LogoAlignment;

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
    public function showformationclient(ManagerRegistry $mr, PaginatorInterface $paginator, Request $request): Response
    {
        $formationsQuery = $mr->getRepository(Formation::class)->createQueryBuilder('f')->getQuery();

        $formations = $paginator->paginate(
            $formationsQuery,
            $request->query->getInt('page', 1),
            6
        );

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

            $this->addFlash('success', 'Formation ajoutée avec succès.');

            return $this->redirectToRoute('app_formation_index');
        }

        return $this->render('formation/formaddformation.html.twig', [
            'formation' => $formation,
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

    #[Route('/formation/{id}/qrcode', name: 'app_formation_qrcode', methods: ['GET'])]
    public function generateQrCode(Formation $formation): Response
    {
        $url = $this->generateUrl('app_formation_show', ['id' => $formation->getId()], true);

        $qrCode = Builder::create()
            ->writer(new PngWriter())
            ->data($url)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(300)
            ->margin(10)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->labelText('Scannez-moi')
            ->labelFont(new NotoSans(20))
            ->build();

        return new Response($qrCode->getString(), Response::HTTP_OK, ['Content-Type' => 'image/png']);
    }

    #[Route('/formation/{id}/edit', name: 'editFormation', methods: ['GET', 'POST'])]
    public function edit(Request $request, Formation $formation, ManagerRegistry $mr): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mr->getManager()->flush();

            $this->addFlash('success', 'Formation mise à jour avec succès.');

            return $this->redirectToRoute('app_formation_index');
        }

        return $this->render('formation/formeditformation.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/formation/{id}/delete', name: 'deleteFormation', methods: ['POST', 'DELETE'])]
    public function deleteFormation(Request $request, ManagerRegistry $mr, FormationRepository $repo, $id): Response
    {
        $formation = $repo->find($id);

        if (!$formation) {
            throw $this->createNotFoundException('Formation non trouvée.');
        }

        if ($this->isCsrfTokenValid('delete'.$formation->getId(), $request->request->get('_token'))) {
            $manager = $mr->getManager();
            $manager->remove($formation);
            $manager->flush();

            $this->addFlash('success', 'Formation supprimée avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute("app_formation_index");
    }

    #[Route('/calendar/events', name: 'calendar_events', methods: ['GET'])]
    public function getEvents(ManagerRegistry $mr): JsonResponse
    {
        $formations = $mr->getRepository(Formation::class)->findAll();
        $events = [];

        foreach ($formations as $formation) {
            $events[] = [
                'title' => $formation->getTitreFor(),
                'start' => $formation->getDateFor()->format('Y-m-d H:i:s'),
                'location' => $formation->getLieuFor(),
                'status' => $formation->getStatutFor(),
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
