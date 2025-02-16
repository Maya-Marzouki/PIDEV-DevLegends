<?php

namespace App\Controller;

use App\Entity\Consultation;
use App\Form\ConsultationType;
use App\Repository\ConsultationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[Route('/consultation')]
class ConsultationController extends AbstractController
{
// Affiche la liste des consultation pour l'Admin
    #[Route('/list', name: 'consultation_index', methods: ['GET'])]
    public function index(ConsultationRepository $consultationRepository): Response
    {
        return $this->render('consultation/ShowConsultation.html.twig', [
            'consultations' => $consultationRepository->findAll(),
        ]);
    }

    // Affiche la liste des consultation pour l'utilisateur
    #[Route('/view/consultation', name: 'consultation_view')]
    public function viewConsultation(ConsultationRepository $consultationRepository): Response
    {
        return $this->render('consultation/viewConsultation.html.twig', [
            'consultations' => $consultationRepository->findAll(),
        ]);
    }
    #[Route('/new', name: 'consultation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $consultation = new Consultation();
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);
        dump($request->request->all());

        // Vérification si le formulaire est soumis
        if ($form->isSubmitted()) {
            dump('Formulaire soumis'); // Debug

            // Vérification si le formulaire est valide
            if ($form->isValid()) {
                dump('Formulaire valide'); // Debug
                
                try {
                    $entityManager->persist($consultation);
                    $entityManager->flush();
                    $this->addFlash('success', 'Consultation ajoutée avec succès !');

                    return $this->redirectToRoute('consultation_view');
                } catch (\Exception $e) {
                    dump('Erreur lors de l’enregistrement : ' . $e->getMessage()); // Debug
                    $this->addFlash('error', 'Une erreur est survenue lors de l’ajout.');
                }
            } else {
                dump($form->getErrors(true)); // Debug des erreurs
                $this->addFlash('error', 'Le formulaire contient des erreurs.');
            }
        }

        return $this->render('consultation/addConsultation.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // #[Route('/new', name: 'consultation_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $consultation = new Consultation();
    //     $form = $this->createForm(ConsultationType::class, $consultation);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($consultation);
    //         $entityManager->flush();

    //         $this->addFlash('success', 'Consultation ajoutée avec succès !');
    //         return $this->redirectToRoute('consultation_index');
    //     }

    //     return $this->render('consultation/addConsultation.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    #[Route('/edit/{id}', name: 'edit_consultation', methods: ['GET', 'POST'])]
    public function edit(Request $request, Consultation $consultation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('consultation_view');
        }

        return $this->render('consultation/editConsultation.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/editAdmin/{id}', name: 'edit_consultation_Admin', methods: ['GET', 'POST'])]
    public function editAdmin(Request $request, Consultation $consultation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('consultation_index');
        }

        return $this->render('consultation/editAdmin.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete_consultation', methods: ['POST'])]
    public function delete(Request $request, Consultation $consultation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$consultation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($consultation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('consultation_view');
    }

    #[Route('/deleteAdmin/{id}', name: 'delete_consultation_Admin', methods: ['POST'])]
    public function deleteAdmin(Request $request, Consultation $consultation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$consultation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($consultation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('consultation_index');
    }

    // Création d'une route pour la validation AJAX
    #[Route('/validate-date', name: 'validate_date', methods: ['POST'])]
    public function validateDate(Request $request, ValidatorInterface $validator): Response
    {
        $date = $request->request->get('date');

        if (!$date) {
            return $this->json(['error' => 'La date est requise.'], 400);
        }

        $dateObject = \DateTime::createFromFormat('Y-m-d', $date);

        if (!$dateObject) {
            return $this->json(['error' => 'Format de date invalide.'], 400);
        }

        // Validation avec Symfony Validator
        $errors = $validator->validate($dateObject, [
            new Assert\NotNull(['message' => 'La date de consultation est obligatoire.']),
            new Assert\GreaterThanOrEqual(['value' => 'today', 'message' => 'La date ne peut pas être antérieure à aujourd’hui.']),
        ]);

        if (count($errors) > 0) {
            return $this->json(['error' => $errors[0]->getMessage()], 400);
        }

        return $this->json(['success' => 'Date valide.']);
    }
}
