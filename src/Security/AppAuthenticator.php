<?php

namespace App\Security;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\SecurityRequestAttributes;

class AppAuthenticator extends AbstractAuthenticator
{
    public function supports(Request $request): bool
    {
        return $request->attributes->get('_route') === 'app_login' && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        // 1. On récupère l'email envoyé dans le formulaire (le fameux name="_username")
        $email = $request->request->getString('_username');

        // 2. On sauvegarde cet email en session pour le réafficher si le mot de passe est faux
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        // 3. On crée et on retourne le fameux "Passeport" que Symfony réclame
        return new Passport(
            new UserBadge($email), // Qui essaie de se connecter ?
            new PasswordCredentials($request->request->getString('_password')), // Avec quel mot de passe ?
            [
                new CsrfTokenBadge('authenticate', $request->request->getString('_csrf_token')), // Le jeton de sécurité
            ]
        );
    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // On redirige l'utilisateur vers la page d'accueil après sa connexion
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        if ($request->hasSession()) {
            $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $exception);
        }

        // On redirige l'utilisateur vers la page de connexion après un échec
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }

    //    public function start(Request $request, ?AuthenticationException $authException = null): Response
    //    {
    //        /*
    //         * If you would like this class to control what happens when an anonymous user accesses a
    //         * protected page (e.g. redirect to /login), uncomment this method and make this class
    //         * implement Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface.
    //         *
    //         * For more details, see https://symfony.com/doc/current/security/experimental_authenticators.html#configuring-the-authentication-entry-point
    //         */
    //    }
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }
}


