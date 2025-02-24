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
            if (method_exists($participation, 'getEmailParticipant') && 
                filter_var($participation->getEmailParticipant(), FILTER_VALIDATE_EMAIL)) {
                $email = (new Email())
                    ->from('noreply@gmail.com')
                    ->to($participation->getEmailParticipant())
                    ->subject('Confirmation de participation')
                    ->text('Votre participation à la formation "' . $formation->getTitreFor() . '" a été confirmée !');

                $mailer->send($email);
            }

            return $this->redirectToRoute('app_formation_index');
        }

        return $this->render('participation/form.html.twig', [ 
            'form' => $form->createView(),
            'formation' => $formation,
        ]);
    }
}
