<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class BookingController extends AbstractController
{

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