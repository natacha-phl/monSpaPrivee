<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/connexion.html', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, UserRepository $userRepository): Response
    {
/*        if ($this->getUser()) {

            $roleUser = $this->getUser()->getRoles();
            if ($roleUser[0] == 'ROLE_ADMIN'){
                return $this->redirectToRoute('admin_dashboard');
            } else {
                return $this->redirectToRoute('default_home');
            }
         }*/





        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/deconnexion.html', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
