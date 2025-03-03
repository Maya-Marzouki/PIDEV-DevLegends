<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\HttpFoundation\RequestStack;

class AppCustomAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private $urlGenerator;
    private $requestStack;

    public function __construct(UrlGeneratorInterface $urlGenerator, RequestStack $requestStack)
    {
        $this->urlGenerator = $urlGenerator;
        $this->requestStack = $requestStack;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('userEmail', '');
        $password = $request->request->get('password', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
{
    $user = $token->getUser();

    // Vérifier si l'utilisateur est bien une instance de User
    if (!$user || !$user instanceof \App\Entity\User) {
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }

    // Vérifier si l'utilisateur est banni
    if ($user->isBanned()) {
        // Accéder à la session via RequestStack
        $session = $this->requestStack->getSession();
        // Stocker le message d'erreur dans la session
        $session->set('custom_error_message', 'Votre compte est banni jusqu\'au ' . $user->getBannedUntil()->format('Y-m-d H:i:s'));
        return new RedirectResponse($this->urlGenerator->generate('app_logout')); // Déconnecter l'utilisateur
    }

    // Récupérer l'ID de l'utilisateur
    $userId = $user->getId();

    // Redirection basée sur le rôle
    if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
        // Rediriger l'admin vers son tableau de bord
        return new RedirectResponse($this->urlGenerator->generate('adminProfile'));
    }

    if (in_array('ROLE_PATIENT', $user->getRoles(), true)) {
        // Rediriger le patient vers son profil
        return new RedirectResponse($this->urlGenerator->generate('userProfile', ['id' => $userId]));
    }

    if (in_array('ROLE_MEDECIN', $user->getRoles(), true)) {
        // Rediriger le médecin vers son profil
        return new RedirectResponse($this->urlGenerator->generate('userProfile', ['id' => $userId]));
    }

    // Redirection par défaut si aucun rôle ne correspond
    return new RedirectResponse($this->urlGenerator->generate('app_login'));
}

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}