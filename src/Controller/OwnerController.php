<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Room;
use App\Entity\Spa;
use App\Entity\User;
use App\Form\OwnerType;
use App\Form\RoomType;
use App\Form\SpaType;
use App\Form\UserType;
use ContainerG9bQChc\getSpaRepositoryService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class OwnerController extends AbstractController
{
    #[Route('owner/sign-up', name:'owner_signup')]
    public function signUp(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $manager){
        $user = new User();
        $user->setRoles(['ROLE_OWNER']);
        $user->setStatus('standby');

        $address = new Address();
        $user->setAddress($address);

        $spa = new Spa();
        $spa->setUser($user);
        $spa->setAddress($address);
        $spa->setStatus('standby');


        $form = $this->createForm(SpaType::class,$spa);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $hashedPassword = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            $manager->persist($user);
            $manager->persist($address);
            $manager->persist($spa);

            $manager->flush();

            return $this->redirectToRoute('default_home');

        }



        return $this->render('owner/sign-up.html.twig', [
            'form'=>$form
        ]);
    }

    #[Route('owner/dashboard', name:'owner_dashboard')]
    public function ownerDashboard(){
        return $this->render('owner/dashboard.html.twig');

    }

    #[Route('owner/add-room-spa', name: 'owner_add_room_spa')]
    public function addRoomSpa(Request $request, EntityManagerInterface $manager)
    {
        $roleUser = $this->getUser()->getRoles();


        if ($roleUser[0] == 'ROLE_OWNER') {


            $userRepository = $manager->getRepository(User::class);
            $user = $userRepository->find($this->getUser());

            $spa = $user->getSpa()->first();//changer car ce n'est pas le first / il faut récup le spa dans le form


           $spas = $user->getSpa();
           //dd($spas);


            $room = new Room();

            $room->setSpa($spa);


            $form = $this->createForm(RoomType::class, $room,[
                'spas'=>$spas
            ]);


            $form->handleRequest($request);

            if ($form->isSubmitted()) {

                $manager->persist($room);

                $manager->flush();
                return $this->redirectToRoute('default_home');
            } else {

                return $this->render('owner/add-room-spa.html.twig', [
                    'form' => $form
                ]);
            }
        } else {
            return $this->redirectToRoute('app_login');
        }
    }



}
