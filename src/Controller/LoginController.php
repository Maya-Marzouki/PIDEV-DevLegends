<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;


class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
        // Afficher simplement le formulaire de connexion
        return $this->render('user/login.html.twig');
    }

    
    #[Route('/logout', name: 'app_logout')]
    public function logout(RequestStack $requestStack, LoggerInterface $logger): Response
    {
        // Récupérer la session
        $session = $requestStack->getSession();

        // Récupérer le message d'erreur de la session
        $errorMessage = $session->get('custom_error_message');

        // Supprimer le message de la session après l'avoir récupéré
        $session->remove('custom_error_message');

        // Invalider la session (fermer la session)
        $session->invalidate();

        // Logger l'événement de déconnexion
        $logger->info('Utilisateur déconnecté avec un message d\'erreur : ' . $errorMessage);

        // Rediriger vers la page de connexion avec un message flash
        if ($errorMessage) {
            $this->addFlash('error', $errorMessage);
        }

        return $this->redirectToRoute('app_login');
    }
}
?>