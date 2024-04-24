<?php

namespace App\Controller;

use App\Entity\Spa;
use App\Entity\User;
use App\Form\SpaType;
use App\Form\UserType;
use App\Repository\BookingRepository;
use App\Repository\RegionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserController extends AbstractController
{
    #[Route('sign-up', name: 'signuppage')]
    public function signUp(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $manager)
    {

        $user = new User();
        $user->setRoles(['ROLE_CUSTOMER']);


        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            $firstName = $form->get('firstName')->getData();
            $lastName = $form->get('lastName')->getData();
            $email = $form->get('email')->getData();

            $user->setFirstName(ucwords(addslashes(trim(htmlentities(strip_tags($firstName))))));
            $user->setLastName(ucwords(addslashes(trim(htmlentities(strip_tags($lastName))))));
            $user->setEmail(strtolower(addslashes(trim(htmlentities(strip_tags($email))))));


            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Felicitation, vous pouvez vous connecter');

            return $this->redirectToRoute('app_login');

        }


        return $this->render('user/sign-up.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('owner-sign-up', name: 'owner_signup')]
    public function ownerSignUp(RegionRepository $regionRepository, Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $manager, MailerInterface $mailer)
    {
        $user = new User();
        $user->setRoles(['ROLE_STANDBY']);
        $user->setStatus('standby');


        $spa = new Spa();
        $spa->setUser($user);
        $spa->setStatus('standby');
        $regionIDF = $regionRepository->find(8);
        $spa->setRegion($regionIDF);


        $form = $this->createForm(SpaType::class, $spa);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $hashedPassword = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            $manager->persist($user);
            $manager->persist($spa);

            $manager->flush();

            $this->addFlash('success', 'Votre demande à bien pris en compte, vous recevrez un email sous 48h vous notifiant sa validation');

            $email = (new TemplatedEmail())
                ->from('gestion@myvipspa.com')
                ->to($user->getEmail())
                ->subject("Confirmation de votre demande d'insription Entreprise.")
                ->htmlTemplate('emails/confirmation.html.twig')
                ->context([
                    'name' => $user->getFirstName(),
                ]);

            $mailer->send($email);


            return $this->redirectToRoute('default_home');

        }


        return $this->render('user/owner-sign-up.html.twig', [
            'form' => $form
        ]);
    }


    #[Route('/mon-compte', name: 'my-account')]
    public function myAccount(UserRepository $userRepository, BookingRepository $bookingRepository)
    {

        $userId = $this->getUser();
        $user = $userRepository->find($userId);

        $bookings = $bookingRepository->findBy([
            'user' => $user
        ]);

        return $this->render('user/my-account.html.twig', [
            'user' => $user,
            'bookings' => $bookings

        ]);
    }


    #[Route('/mon-compte-modifier', name: 'my-account-modify')]
    public function myAccountModify(Request $request, UserRepository $userRepository, EntityManagerInterface $manager)
    {
        if ($this->getUser()) {

            $userId = $this->getUser();
            $user = $userRepository->find($userId);

            $firsName = $request->query->get('firstName');
            $lastName = $request->query->get('lastName');
            $email = $request->query->get('email');
            $street = $request->query->get('street');
            $zipCode = $request->query->get('zipCode');
            $city = $request->query->get('city');


            $user->setFirstName($firsName);
            $user->setLastName($lastName);
            $user->setEmail($email);
            $user->setStreet($street);
            $user->setZipCode($zipCode);
            $user->setCity($city);


            $manager->persist($user);
            $manager->flush();


            return $this->redirectToRoute('my-account');
        }

        return $this->redirectToRoute('my-account');

    }


    #[Route('/mon-compte-supprimer', name: 'my-account-delete')]
    public function myAccountDelete(SessionInterface $session, Request $request, UserRepository $userRepository, EntityManagerInterface $manager)
    {
        if ($this->getUser()) {

//    $userId=$request->query->get('userId');
            $user = $this->getUser();
            $session = new Session();
            $session->invalidate();
            $manager->remove($user);
            $manager->flush();


            return $this->redirectToRoute('app_logout');
        } else {
            return $this->redirectToRoute('default_home');
        }

    }
/*
    #[Route('/forgotten-password', name: 'forgotten-password')]
    public function forgottenPassword()
    {


        return $this->render('user/forgotten-password.html.twig');


    }

    #[Route('/password-reset-request', name: 'password-reset-request')]
    public function passwordReset(Request $request, MailerInterface $mailer, UserRepository $userRepository)
    {

        $userEmail = $request->request->get('email');
        $user = $userRepository->findOneBy(['email' => $userEmail]);

        $url = $this->generateUrl('password-reset-action',[], UrlGeneratorInterface::ABSOLUTE_URL);

        if ($user !== null) {

            $userName = $user->getFirstName();


            $email = (new TemplatedEmail())
                ->from('gestion@myvipspa.com')
                ->to($userEmail)
                ->subject("Lien de réinitialisation de votre mot de passe")
                ->htmlTemplate('emails/password-reset.html.twig')
                ->context([
                    'name' => $userName,
                    'url' => $url
                ]);

            $mailer->send($email);

            $this->addFlash('sucess', 'Un email contenant un lien de réinitialisation de votre mot de passe vous a été envoyé');


            return $this->redirectToRoute("default_home");

        }

        $this->addFlash('sucess', 'Un email contenant un lien de réinitialisation de votre mot de passe vous a été envoyé');


        return $this->redirectToRoute("default_home");


    }

    #[Route('/password-reset-action', name: 'password-reset-action')]
    public function passwordResetAction()
    {

    }*/


}