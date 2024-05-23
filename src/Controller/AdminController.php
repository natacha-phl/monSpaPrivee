<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminType;
use App\Repository\BookingRepository;
use App\Repository\SpaRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{




    #[Route('admin/create-account', name: 'admin_create_account')]
    public function adminCreateAccount(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $manager){
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);

        $form = $this->createForm(AdminType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $hashedPassword = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('default_home'); //changer mettre le nom de la route de l'home admin

        }

        return $this->render('admin/create-account.html.twig',[
            'form'=>$form
        ]);
    }




    #[Route('admin/accounts-validation', name: 'admin_accounts_validation')]
    public function accountsValidation(SpaRepository $spaRepository){

        //$listOfSpasToBeValidated = [];

        $spasToBeValidated = $spaRepository->findBy(
            ['status'=>'standby']
        );


        return $this->render('admin/accounts-validation.html.twig',[
            'spas'=>$spasToBeValidated,
        ]);

    }


    #[Route('admin/accounts-validation-allow', name: 'admin_accounts_validation_allow')]
    public function accountsValidationAllow(EntityManagerInterface $manager, Request $request, SpaRepository $spaRepository, UserRepository $userRepository)
    {

        $spaId = $request->get('spaId');
        $spa = $spaRepository->find($spaId);
        $spa->setStatus('confirmed');

        $spaUserId = $spa->getUser();
        $user = $userRepository->find($spaUserId);
        $user->setRoles(['ROLE_OWNER']);
        $user->setStatus('confirmed');

        $manager->flush();




        return $this->redirectToRoute('admin_accounts_validation');




    }

    #[Route('admin/accounts-validation-deny', name: 'admin_accounts_validation_deny')]
    public function accountsValidationDeny(EntityManagerInterface $manager, Request $request, SpaRepository $spaRepository)
    {

        $spaId = $request->get('spaId');
        $spa = $spaRepository->find($spaId);
        $manager->remove($spa);
        $manager->flush();

        return $this->redirectToRoute('admin_accounts_validation');


    }



    #[Route('admin/transactions', name: 'admin_transactions')]
    public function transactions(BookingRepository $bookingRepository){
        $bookings = $bookingRepository->allTransactionOrderRecent();



        return $this->render('admin/transactions.html.twig', [
            'bookings'=>$bookings
        ]);
    }


    #[Route('admin/admin-accounts-list', name: 'admin_accounts_list')]
    public function adminAccountsList(UserRepository $userRepository){
        $role = "ROLE_ADMIN";
        $admins = $userRepository->findByRoleAdmin($role);


        return $this->render('admin/admin-accounts-list.html.twig',[
            'admins'=>$admins
        ]);
    }

    #[Route('admin/admin-remove-account', name: 'admin_remove-account')]
    public function adminRemoveAccount(UserRepository $userRepository, Request $request, EntityManagerInterface $manager){
        $adminId =$request->query->get('adminId');
        $user = $userRepository->find($adminId);
        $manager->remove($user);
        $manager->flush();

        return $this->redirectToRoute('admin_accounts_list');
    }



    #[Route('admin/owner-list', name : 'owner_accounts_list')]
    public function ownerAccountsList(UserRepository $userRepository){

        $role = "ROLE_OWNER";
        $owners = $userRepository->findByRoleAdmin($role);


        return $this->render('admin/owner-accounts-list.html.twig',[
            'owners'=>$owners
        ]);
    }

    #[Route('admin/customer-list', name : 'customer_accounts_list')]
    public function customerAccountsList(UserRepository $userRepository){

        $role = "ROLE_CUSTOMER";

        $customers = $userRepository->findByRoleAdmin($role);

        return $this->render('admin/customer-accounts-list.html.twig',[
            'customers'=>$customers
        ]);
    }

    #[Route('admin/spas-list', name : 'admin_spas_list')]
    public function spasList(SpaRepository $spaRepository){

        $spas = $spaRepository->findAll();

        return $this->render('admin/spas-list.html.twig',[
            'spas'=>$spas
        ]);
    }

}
