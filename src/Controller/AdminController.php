<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{



    #[Route('admin/dashboard', name: 'admin_dashboard')]
    public function adminDashboard(){
        return $this->render('admin/dashboard.html.twig');
    }
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
    public function accountsValidation(){
        return $this->render('admin/accounts-validation.html.twig');
    }

    #[Route('admin/account-validation-details')]
    public function accountValidationDetails(){
        return $this->render('admin/account-validation-details.html.twig');
    }

    #[Route('admin/transactions')]
    public function transactions(){
        return $this->render('admin/transactions.html.twig');
    }

    #[Route('admin/transaction-details')]
    public function transactionDetails(){
        return $this->render('admin/transaction-details.html.twig');
    }

    #[Route('admin/users')]
    public function users(){
        return $this->render('admin/users.html.twig');
    }

    #[Route('admin/user-details')]
    public function userDetails(){
        return $this->render('admin/user-details.html.twig');
    }

}
