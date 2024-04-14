<?php

namespace App\Controller;

use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{

    #[Route('/panier', name: 'cart')]
    public function cart(RoomRepository $roomRepository, SessionInterface $session, \Symfony\Component\HttpFoundation\Request $request)
    {
        // on recupère la session

        $panier = $session->get('panier', []);


        $hours = $session->get('hours');

        $panierWithData = [];



        foreach ($panier as $eachBooking) {
            $idRoom= $eachBooking[0];
            $bookingHours = $eachBooking[1];
            $lineNumber = $eachBooking[2];
                $panierWithData[] = [
                    'room' => $roomRepository->find($idRoom),
                    'bookingHours' =>$bookingHours,
                    'lineNumber'=>$lineNumber,

                ];
            //}
        }

        //dd($lineNumber);

        $total = 0;




        foreach ($panierWithData as $item) {
            $itemPrice = $item['room']->getpriceHour();
            $bookingHours = $item['bookingHours'];
            $totalItem = $itemPrice * $bookingHours;
            $total += $totalItem;

        }

        return $this->render('cart/panier.html.twig'
            , [
                'items' => $panierWithData,
                'total' => $total,
                //'bookingHours' => $bookingHours

            ]);



    }
    #[Route('/panier/ajouter/{id}', name:'cart_add' )]

    public function cartAdd($id, SessionInterface $session, \Symfony\Component\HttpFoundation\Request $request)
    {
       // récupere le hours avec get du formulaire qui est dans room.html.twig
        $hours = $request->query->get('hours');

        $session->set('hours', $hours);

        // on récupère le panier de la session

        $panier = $session->get('panier', []);

        $nombreDeLignePanier = count($panier);
        //dd($nombreDeLignePanier);



        if (!is_array($panier)) {
            $panier=[];
        }


        // on ajoute le room du produit dans le panier

        $panier[] = array($id, $hours, $nombreDeLignePanier); //eventuellement le nombre de ligne panier et pareil pour la variable en haut



        // on met à a jour le panier dans la session

        $session->set('panier', $panier);
        //dd($panier);


        return $this->redirectToRoute('cart');


    }

    #[Route('/panier/remove', name: 'cart_remove')]
    public function remove(SessionInterface $session, Request $request)
    {

        //$lineNumber = $request->query->get('item.lineNumber');
        $lineNumber = $request->query->get('itemLineNumber');
        //dd($lineNumber);

        $panier = $session->get('panier', []);
        if (!empty($panier[$lineNumber])) {
            unset($panier[$lineNumber]);
        }

        $session->set('panier', $panier);
        return $this->redirectToRoute('cart');

    }


}