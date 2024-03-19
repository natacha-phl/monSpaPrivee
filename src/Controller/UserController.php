<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('sign-up', name: 'signuppage')]
    public function signUp(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $manager){

        $user = new User();
        $user->setRoles(['ROLE_CUSTOMER']);
        $address = new Address() ;
        $user->setAddress($address);

        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $hashedPassword = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            $manager->persist($user);
            $manager->persist($address);
            $manager->flush();

            $this->addFlash('success', 'Felicitation, vous pouvez vous connecter');

            return $this->redirectToRoute('app_login');

        }


        /*$address = new Address() ;
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $manager->persist($address);
            $manager->flush();

        }*/



        return $this->render('user/sign-up.html.twig', [
            'form'=> $form
        ]);
    }

    #[Route('/mon-compte')]
    public function myAccount(){
        return $this->render('user/my-account.html.twig');
    }




}