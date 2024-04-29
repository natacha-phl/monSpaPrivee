<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\Spa;
use App\Entity\User;
use App\Form\RoomModificationType;
use App\Form\RoomType;
use App\Form\SpaForPartnersType;
use App\Form\SpaType;
use App\Repository\BookingRepository;
use App\Repository\RegionRepository;
use App\Repository\RoomRepository;
use App\Repository\SpaRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


class OwnerController extends AbstractController
{

    #[Route('owner/spas', name: 'owner-spas')]
    public function ownerSpas(SpaRepository $spaRepository)
    {
        $user = $this->getUser();
        $ownerSpas = $spaRepository->findBy([
            'user' => $user
        ]);


        return $this->render('owner/spas.html.twig', [
            'ownerSpas' => $ownerSpas
        ]);
    }

    #[Route('owner/modification-spa-room', name: 'owner-modification-spa-room')]
    public function ownerModificationSpa(RoomRepository $roomRepository, EntityManagerInterface $manager, Request $request)
    {
        if ($this->getUser()) {
            $ownerId = $this->getUser()->getUserIdentifier();
            $roomId = $request->query->get('roomId');
            $room = $roomRepository->find($roomId);
            $spa = $room->getSpa();


            if ($spa->getUser()->getUserIdentifier() === $ownerId) {


                $form = $this->createForm(RoomModificationType::class, $room);

                $form->setData($room);

                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    // Enregistrer les modifications dans la base de données
                    $manager->persist($room);
                    $manager->flush();

                    // Rediriger l'utilisateur vers une autre page après la modification
                    return $this->redirectToRoute('owner_rooms');
                }

                // Afficher le formulaire de modification dans le template
                return $this->render('owner/modification-spa-room.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

        }


        return $this->redirectToRoute('default_home');

    }

    #[Route('owner/modification-spa-room-delete', name: 'owner-modification-spa-room-delete')]
    public function ownerModificationSpaDelete(RoomRepository $roomRepository, EntityManagerInterface $manager, Request $request)
    {
        if ($this->getUser()) {
            $ownerId = $this->getUser()->getUserIdentifier();
            $roomId = $request->query->get('roomId');
            $room = $roomRepository->find($roomId);
            $spa = $room->getSpa();


            if ($spa->getUser()->getUserIdentifier() === $ownerId) {
                $manager->remove($room);
                $manager->flush();

                $this->redirectToRoute("owner_rooms");
            }
        }
        return $this->redirectToRoute("default_home");
    }


    /* #[Route('owner/modification-spa-room-submit', name: 'owner-modification-spa-room-submit')]
     public function ownerModificationSpaSubmit(UploadHandler $uploadHandler,EquipmentRepository $equipmentRepository, RoomRepository $roomRepository, Request $request, EntityManagerInterface $manager)
     {

         if ($this->getUser()) {
             $roomId = $request->query->get('roomId');
             $room = $roomRepository->find($roomId);
             $spa = $room->getSpa();
             $owner = $spa->getUser();

             if ($owner === $this->getUser()) {


                 $roomName = $request->query->get('roomName');
                 $roomDescription = $request->query->get('roomDescription');
                 $roomCapacity = $request->query->get('roomCapacity');
                 $roomPriceHour = $request->query->get('roomPriceHour');
                 $roomImage = $request->files->get('roomImage');


                 $roomEquipment = $request->get('roomEquipment');


                 $currentEquipments = $room->getEquipment();

                 $currentEquipmentIds = [];
                 foreach ($currentEquipments as $equipment) {
                     $currentEquipmentIds[] = $equipment->getId();
                 }


                 // Comparer les équipements envoyés depuis le formulaire avec ceux déjà associés
                 $newEquipments = [];
                 foreach ($roomEquipment as $equipmentId) {
                     if (!in_array($equipmentId, $currentEquipmentIds)) {
                         $newEquipments[] = $equipmentRepository->find($equipmentId);
                     }
                 }

                 // Supprimer les équipements actuels de la salle
                 foreach ($currentEquipments as $equipment) {
                     $room->removeEquipment($equipment);
                 }

                 // Ajouter les nouveaux équipements à la salle
                 foreach ($newEquipments as $equipment) {
                     $room->addEquipment($equipment);
                 }

                 $room->setName($roomName);
                 $room->setDescription($roomDescription);
                 $room->setCapacity($roomCapacity);
                 $room->setPriceHour($roomPriceHour);


                 if ($roomImage instanceof UploadedFile) {
                     // Utiliser VichUploaderBundle pour télécharger et renommer le fichier
                 $newFileName = $uploadHandler->upload($roomImage, 'cdd');

                 // Mettre à jour le nom du fichier dans l'entité Room
                 $room->setImageFile($newFileName);
             }

                 $manager->persist($spa);

                 $manager->flush();


                 return $this->redirectToRoute('owner_rooms');


             }

             return $this->redirectToRoute('owner_rooms');


         }
         return  $this->redirectToRoute('default_home');


     }*/


    #[Route('owner/reservations', name: 'owner_bookings')]
    public function ownerBookings(Request $request, BookingRepository $bookingRepository, RoomRepository $roomRepository, SpaRepository $spaRepository, EntityManagerInterface $manager)
    {

        $user = $this->getUser();

        $spas = $spaRepository->findBy([
            'user' => $user
        ]);

        $roomsOfSpa = $roomRepository->findBy([
            'spa' => $spas
        ]);

        $bookings = $bookingRepository->findBy([
            'room' => $roomsOfSpa
        ]);


        return $this->render('owner/bookings.html.twig', [
            'boookings' => $bookings
        ]);

    }

    #[Route('owner/validation-reservations', name: 'owner-bookings-validation')]
    public function bookingValidation(Request $request, BookingRepository $bookingRepository, RoomRepository $roomRepository, SpaRepository $spaRepository, EntityManagerInterface $manager)
    {

        $user = $this->getUser();

        $spas = $spaRepository->findBy([
            'user' => $user
        ]);

        $roomsOfSpa = $roomRepository->findBy([
            'spa' => $spas
        ]);

        $bookings = $bookingRepository->findBy([
            'room' => $roomsOfSpa,
            'status' => 'unbooked'
        ]);


        return $this->render('owner/bookings-validation.html.twig', [
            'boookings' => $bookings
        ]);

    }


    #[Route('owner/schedule-booking', name: 'schedule-booking')]
    public function sceduleBooking(Request $request, BookingRepository $bookingRepository, EntityManagerInterface $manager)
    {

        $bookingId = $request->get('bookingId');
        $bookingStartDate = $request->get('bookingStartDate');


        $bookingStartDateConverted = DateTimeImmutable::createFromFormat('Y-m-d\TH:i', $bookingStartDate);


        $booking = $bookingRepository->find($bookingId);

        $booking->setStartDate($bookingStartDateConverted);
        $booking->setStatus('booked');

        $manager->persist($booking);
        $manager->flush();


        return $this->redirectToRoute('owner-bookings-validation');

    }

    #[Route('owner/add-room-spa', name: 'owner_add_room_spa')]
    public function addRoomSpa(Request $request, EntityManagerInterface $manager)
    {
        $roleUser = $this->getUser()->getRoles();


        if ($roleUser[0] == 'ROLE_OWNER') {


            $userRepository = $manager->getRepository(User::class);
            $user = $userRepository->find($this->getUser());

//            $spa = $user->getSpa()->first();//changer car ce n'est pas le first / il faut récup le spa dans le form
//
//
            $spas = $user->getSpa();
//           //dd($spas);


            $room = new Room();

//            $room->setSpa($spa);


            $form = $this->createForm(RoomType::class, $room, [
                'spas' => $spas
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


    #[Route('owner/rooms', name: 'owner_rooms')]
    public function ownerRooms(Request $request, RoomRepository $roomRepository, SpaRepository $spaRepository)
    {

        $roleUser = $this->getUser()->getRoles();


        if ($roleUser[0] == 'ROLE_OWNER') {

            $spas = $spaRepository->findBy(
                ['user' => $this->getUser()]
            );

            $rooms = $roomRepository->findBy(
                ['spa' => $spas]
            );

            return $this->render('owner/rooms.html.twig', [
                'rooms' => $rooms
            ]);

        } else {

            return $this->redirectToRoute('default_home');
        }


    }

    #[Route('owner/add-spa', name: 'owner_add_spa')]
    public function ownerAddSpa(Request $request, EntityManagerInterface $manager, RegionRepository $regionRepository)

    {

        if ($this->getUser()) {
            $roleUser = $this->getUser()->getRoles();


            if ($roleUser[0] == 'ROLE_OWNER') {
                $spa = new Spa();
                $spa->setStatus('standby');

                $regionIDF = $regionRepository->find(8);
                $spa->setRegion($regionIDF);

                $spa->setUser($this->getUser());

                $form = $this->createForm(SpaForPartnersType::class, $spa);

                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {


                    $manager->persist($spa);

                    $manager->flush();
                    return $this->redirectToRoute('owner-spas');


                } else {
                    return $this->render('owner/add-spa.html.twig', [
                        'form' => $form
                    ]);
                }
            }
            return $this->redirectToRoute('default_home');
        }

    }


}
