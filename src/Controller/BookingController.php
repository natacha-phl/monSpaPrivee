<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class BookingController extends AbstractController

{
    private $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }


    #[Route('/reserver', name: 'booking_reserver')] // j'ai enlevé l'id car on récupère le panier je l'ai aussi supprimé des paramètres de ma fonction

    public function book(RoomRepository $roomRepository, UserRepository $userRepository, Request $request, EntityManagerInterface $manager, SessionInterface $session)
    {

        $panier = $session->get('panier', []);


        foreach ($panier as $eachBooking) {

            $idRoom = $eachBooking[0];
            $bookingHours = $eachBooking[1];
            $lineNumber = $eachBooking[2];
            $panierWithData[] = [
                'room' => $roomRepository->find($idRoom),
                'bookingHours' => $bookingHours,
                'lineNumber' => $lineNumber,

            ];
            //}
        }

        //dd($lineNumber);


        foreach ($panierWithData as $item) {

            $room = $item['room'];
            $itemPrice = $room->getpriceHour();
            $bookingHours = $item['bookingHours'];
            $totalAmount = $itemPrice * $bookingHours;

            $booking = new Booking();

            $user = $this->getUser();

            $dateNow = new \DateTimeImmutable();
            $dateNow->format('Y-m-d H:i:s');

            $booking->setStatus('unbooked');
            $booking->setUser($user);

            $booking->setRoom($room);


            $booking->setCreatedAt($dateNow);

            $booking->setDuration($bookingHours);

            $booking->setTotalAmount($totalAmount);


            /*            $form = $this->createForm(BookingType::class, $booking, [
                            'room' => $room,
                        ]);


                        $form->handleRequest($request);*/

//            if ($form->isSubmitted()) {

            $manager->persist($booking);

            $manager->flush();


        }

        return $this->redirectToRoute('default_home');


        // return $this->redirectToRoute('default_home');


    }








/*        $room = $roomRepository->find($id);

        $user = $this->getUser();

        $dateNow = new \DateTimeImmutable();
        $dateNow->format('Y-m-d H:i:s');



        $booking = new Booking();

        $booking->setStatus('standby');
        $booking->setUser($user);

        $booking->setRoom($room);


        $booking->setCreatedAt($dateNow);


        $form = $this->createForm(BookingType::class, $booking, [
            'room' => $room,
        ]);


        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $manager->persist($booking);

            $manager->flush();

            // récupérzttion des données pour  envoi d'un email au spa user

            $spa = $room->getSpa();
            $spaOwner = $spa->getUser();
            $spaOwnerEmail = $spaOwner->getEmail();

            //$userCustomerId = $user->getUserIdentifier();
            $userCustomer = $userRepository->find($user);
            $userCustomerEmail= $userCustomer->getEmail();

            // Envoi d'email à l'adresse du propriétaire du spa
            $subject = 'Nouvelle  demande de réservation';
            $body = "Bonjour,\n\nUne nouvelle réservation a été effectuée pour votre spa consultez votre tableau de bord pour.\n\nCordialement,\nVotre site de réservation";
            //$recipient = $spaOwnerEmail;
            $recipient='natacha.pamphil@gmail.com';
            $this->emailService->sendEmail($recipient, $subject, $body);

            // Envoi d'email à l'utilisateur qui a effectué la réservation
            $subject = 'Votre demande de réservation à bien été envoyée';
            $body = "Bonjour,\n\nVotre réservation a bien été enregistrée.\n\nCordialement,\nVotre site de réservation";
            //$recipient = $userCustomerEmail;
            $this->emailService->sendEmail($recipient, $subject, $body);

            return $this->redirectToRoute('default_home');



           // return $this->redirectToRoute('default_home');


        } else {
            return $this->render('booking/reserver.html.twig', [
                'form'=>$form,
                'room' => $room,
            ]);
        }*/

    //}
    #[Route('/reservation-revue')] //page de revue de la reservation avec un bouton paiement
    public function bookingReview (){
        return $this->render('booking/reservation-revue.html.twig');
    }

    #[Route('/paiement')] // page paiement après avoir cliquer sur le bouton paiement dans la page reservation revue
    public function payment (){

        if($this->getUser()){
            $roleUser = $this->getUser()->getRoles();
            if ($roleUser[0] == 'ROLE_CUSTOMER') {
                return $this->render('booking/payment.html.twig');
            } else return $this->redirectToRoute('app_login');
        } else return $this->redirectToRoute('app_login');
    }

    #[Route('/confirmation')] //page confirmation du paiement
    public function confirmation (){
        return $this->render('confirmation.html.twig');
    }



}