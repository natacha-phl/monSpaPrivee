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


    #[Route('/reservation-revue')] //page de revue de la reservation avec un bouton paiement
    public function bookingReview()
    {
        return $this->render('booking/reservation-revue.html.twig');
    }

    #[Route('/paiement')] // page paiement après avoir cliquer sur le bouton paiement dans la page reservation revue
    public function payment()
    {

        if ($this->getUser()) {
            $roleUser = $this->getUser()->getRoles();
            if ($roleUser[0] == 'ROLE_CUSTOMER') {
                return $this->render('booking/payment.html.twig');
            } else return $this->redirectToRoute('app_login');
        } else return $this->redirectToRoute('app_login');
    }

    #[Route('/confirmation')] //page confirmation du paiement
    public function confirmation()
    {
        return $this->render('confirmation.html.twig');
    }


}