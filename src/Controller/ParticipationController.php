<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Participation;
use App\Form\ParticipationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class ParticipationController extends AbstractController
{
    #[Route('/participation', name: 'app_participation')]
    public function index(): Response
    {
        return $this->render('participation/index.html.twig', [
            'controller_name' => 'ParticipationController',
        ]);
    }

    #[Route('/formation/{id}/participation', name: 'formation_participation')]
    public function participer(
        Request $request, 
        ManagerRegistry $mr, 
        MailerInterface $mailer,
        Formation $formation
    ): Response {
        // Créer une nouvelle participation
        $participation = new Participation();
        $participation->setFormation($formation);
        $participation->setDateParticipation(new \DateTime());

        // Créer le formulaire
        $form = $this->createForm(ParticipationType::class, $participation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $mr->getManager();
            $manager->persist($participation);
            $manager->flush();

            // Vérification de l'email et envoi
            $emailParticipant = $participation->getEmailParticipant();
            if (!empty($emailParticipant) && filter_var($emailParticipant, FILTER_VALIDATE_EMAIL)) {
                $email = (new Email())
                    ->from('noreply@gmail.com') // Remplace par ton adresse d'envoi
                    ->to($emailParticipant)
                    ->subject('Confirmation de participation')
                    ->text('Bonjour ' . $participation->getNomParticipant() . ',\n\n' .
                        'Votre participation à la formation "' . $formation->getTitreFor() . '" a été confirmée !');

                // Envoi de l'email
                $mailer->send($email);

                // Message de succès pour l'utilisateur
                $this->addFlash('success', 'Votre inscription a été enregistrée et un email de confirmation a été envoyé.');
            } else {
                // Message d'erreur si l'email est invalide
                $this->addFlash('error', 'L\'email fourni est invalide.');
            }

            // Redirection après soumission réussie
            return $this->redirectToRoute('formationclient');
        }

        return $this->render('participation/form.html.twig', [ 
            'form' => $form->createView(),
            'formation' => $formation,
        ]);
    }
}
