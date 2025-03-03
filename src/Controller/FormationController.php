<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Omines\DataTablesBundle\DataTableFactory;
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
    public function showFormationClient(ManagerRegistry $mr, PaginatorInterface $paginator, Request $request): Response
    {
        $formationsQuery = $mr->getRepository(Formation::class)->createQueryBuilder('f')->getQuery();
        $formations = $paginator->paginate($formationsQuery, $request->query->getInt('page', 1), 6);

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
            $em = $mr->getManager();
            $em->persist($formation);
            $em->flush();

            $this->addFlash('success', 'Formation ajoutée avec succès.');
            return $this->redirectToRoute('app_formation_index');
        }

        return $this->render('formation/formaddformation.html.twig', [
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

    #[Route('/formation/{id}/edit', name: 'editFormation', methods: ['GET', 'POST'])]
    public function edit(Request $request, Formation $formation, ManagerRegistry $mr): Response
    {
        if (!$formation) {
            throw $this->createNotFoundException('Formation non trouvée.');
        }

        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mr->getManager()->flush();
            $this->addFlash('success', 'Formation mise à jour avec succès.');

            return $this->redirectToRoute('app_formation_index');
        }

        return $this->render('formation/formeditformation.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/formation/{id}/delete', name: 'deleteFormation', methods: ['POST'])]
    public function deleteFormation(Request $request, ManagerRegistry $mr, FormationRepository $repo, $id): Response
    {
        $formation = $repo->find($id);

        if (!$formation) {
            throw $this->createNotFoundException('Formation non trouvée.');
        }

        if ($this->isCsrfTokenValid('delete'.$formation->getId(), $request->request->get('_token'))) {
            $em = $mr->getManager();
            $em->remove($formation);
            $em->flush();
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
        $events = array_map(fn($formation) => [
            'title' => $formation->getTitreFor(),
            'start' => $formation->getDateFor()->format('Y-m-d H:i:s'),
            'location' => $formation->getLieuFor(),
            'status' => $formation->getStatutFor(),
        ], $formations);

        return new JsonResponse($events);
    }

    #[Route('/calendar', name: 'calendar_view')]
    public function calendar(): Response
    {
        return $this->render('formation/calendar.html.twig');
    }

    #[Route('/formation/pdf', name: 'formation_pdf')]
public function generatePdf(ManagerRegistry $mr): Response
{
    $this->addFlash('info', 'Début de la génération du PDF');

    // Vérifier si les formations existent
    $formations = $mr->getRepository(Formation::class)->findAll();
    if (!$formations) {
        $this->addFlash('error', 'Aucune formation trouvée.');
        return $this->redirectToRoute('app_formation_index');
    }

    // Options pour DomPDF
    $options = new Options();
    $options->set('defaultFont', 'Arial');

    // Créer l’instance DomPDF
    $dompdf = new Dompdf($options);

    // Vérifier si le fichier logo existe
    $logoPath = $this->getParameter('kernel.project_dir') . '/public/images/logo.png';
    if (!file_exists($logoPath)) {
        $this->addFlash('error', 'Le fichier du logo est introuvable.');
        return $this->redirectToRoute('app_formation_index');
    }

    // Générer le HTML
    $html = $this->renderView('formation/pdf_template.html.twig', [
        'formations' => $formations,
        'logo' => $logoPath,
        'date' => new \DateTime(),
    ]);

    // Charger le HTML dans DomPDF
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Télécharger le fichier PDF
    return new StreamedResponse(function () use ($dompdf) {
        echo $dompdf->output();
    }, 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="formations.pdf"'
    ]);
}

#[Route('/formations/datatables', name: 'formations_datatables')]
public function formationsDatatables(DataTableFactory $dataTableFactory, Request $request): Response
{
    $table = $dataTableFactory->create(FormationListType::class);
    $table->handleRequest($request);

    if ($table->isCallback()) {
        return $table->getResponse();
    }

    return $this->render('formation/datatables.html.twig', [
        'datatable' => $table,
    ]);
}

}
