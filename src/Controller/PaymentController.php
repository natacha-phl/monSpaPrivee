<?php

namespace App\Controller;

use App\Repository\BookingRepository;
use App\Repository\RoomRepository;
use http\Env\Request;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use function PHPUnit\Framework\isEmpty;

class PaymentController extends AbstractController
{

    #[Route('/payment/success', name: 'payment_success')]
    public function success()
    {
//        $this->addFlash('notice', 'Votre reservation a bien été effectué !');
        return $this->render('payment/success.html.twig', [
        ]);

    }

    #[Route('/payment/error', name: 'payment_error')]
    public function error()
    {
//        $this->addFlash('notice', 'Une erreur est survenue lors de votre paiement ! Veuillez vérifier vos données.');
        return $this->render('payment/error.html.twig', [
        ]);

    }


    #[Route('/booking/create-session-stripe', name: 'payment_stripe')]
    public function stripeCheckout(SessionInterface $session, RoomRepository $roomRepository) //parce qu'on va pas faire de render on va rediriger

    {
        $panier = $session->get('panier', []);

        if (empty($panier)) {
            $this->redirectToRoute('cart');
        }

        \Stripe\Stripe::setApiKey('sk_test_51OxVzKHK6LMS7CIDqI77YVyrofEQzKjgDrUlOQpfEuZsZ5BXZaOrW85R11tkHpPgewXqd5wy2KGhiHQRDvwXllES00b1y1JGKY');
        $booking = [];

        foreach ($panier as $eachBooking) {
            $idRoom = $eachBooking[0];
            $bookingHours = $eachBooking[1];
            $booking[] = [
                'room' => $roomRepository->find($idRoom),
                'bookingHours' => $bookingHours,
            ];
        }


        foreach ($booking as $item) {

            $itemName = $item['room']->getName();
            $itemPriceHour = $item['room']->getPriceHour() . '00';
            $itemBookingHours = $item['bookingHours'];
            $itemDescription = 'Formule ' . $itemBookingHours . ' heure(s)';


            $checkoutItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $itemPriceHour,
                    'product_data' => [
                        'name' => $itemName,
                        'description' => $itemDescription
                    ]
                ],
                'quantity' => $itemBookingHours,
            ];

        }


        /*        $checkout_session = \Stripe\Checkout\Session::create(
                    [
                    'customer_email' => $this->getUser()->getUserIdentifier(),
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => [
                            'currency' => 'eur',
                            'unit_amount' => $itemPriceHour,
                            'product_data' => [
                                'name' => $itemName,
                                'description'=>$itemDescription
                            ]
                        ],
                        'quantity' => $itemBookingHours,
                    ]],
                    'mode' => 'payment',
                    'success_url' => $this->generateUrl('payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
                    'cancel_url' => $this->generateUrl('payment_error', [], UrlGeneratorInterface::ABSOLUTE_URL),
                ]);*/


        $checkout_session = \Stripe\Checkout\Session::create(
            [
                'customer_email' => $this->getUser()->getUserIdentifier(),
                'payment_method_types' => ['card'],
                'line_items' => $checkoutItems, // Utiliser les éléments de paiement construits précédemment
                'mode' => 'payment',
                'success_url' => $this->generateUrl('payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url' => $this->generateUrl('payment_error', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);

        $session->invalidate();

        return new RedirectResponse($checkout_session->url);
    }
}