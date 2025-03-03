<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendReclamationConfirmation(string $toEmail): void
    {
        // Ajoutez un log pour vérifier que la méthode est appelée
        error_log("Envoi de l'email à: " . $toEmail);

        $email = (new Email())
            ->from('mahdisafraoui10@gmail.com')
            ->to($toEmail)
            ->subject('Votre réclamation a été traitée')
            ->text("Bonjour,\n\nVotre réclamation a été traitée. Merci pour votre patience.\n\nCordialement, \nL'équipe");

        $this->mailer->send($email);
    }

    public function sendAvisConfirmation(string $toEmail): void
    {
        $email = (new Email())
            ->from('hmem.ali2003@gmail.com')
            ->to($toEmail)
            ->subject('Votre avis a été traitée')
            ->text("Bonjour,\n\nVotre avis a été traitée. Merci pour votre patience.\n\nCordialement, \nL'équipe");

        $this->mailer->send($email);
    }
}