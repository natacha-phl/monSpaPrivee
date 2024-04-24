<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AppUserAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', ''); //récupère l'email depuis la requête

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email); // sauvegarde l'email dans la session, dans les cas ou il y a erreur d'auth ça permet de remettre 'l'email dans les champs

        return new Passport( ///fabrication du passport qui va
            new UserBadge($email), //1er param pour créer le pasport qui va etre vérifié par diff méthode badge qui corresspond à l'utilisateur bage qui donne des infos sur l'utilisateur et notamment sont identifiant (moi c'est l'email) une sorte de tempon qui contient les info de l'utilisateur
            new PasswordCredentials($request->request->get('password', '')), //2eme param systeme d'auth... on lui passe donc un autre badge qui contient le mdp
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')), //3eme param tampon pour dire qu'il faut validé le token csrf
                new RememberMeBadge(),   //4eme tempon qui dit que cette auth on doit s'en souvenir // le composant sécu va regarder le passport et il y a deux situation possible en gros il va y avoir un listener UserChecker Listener qui cont venir écouter le processus d'authentification qui vont récup le passport et vérifier les badges -- le User Checker Listener
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        } // redirige l'utilisareur vers la page qu'il essayait d'avoir au début // c'est gérer par "TRAIT" TargetPathTrait qui se trouve plus haut

        // For example:
        // return new RedirectResponse($this->urlGenerator->generate('some_route'));
        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
        return new RedirectResponse($this->urlGenerator->generate('default_home')); // s'il a pas de chemin cible et qu'il est directement arrivé vers la page de connexion il est redirigé là / l'URL GEnerator interface est un service qui permet de générer des url
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}




