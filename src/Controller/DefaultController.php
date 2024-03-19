<?php

namespace App\Controller;

use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'default_home')]
    public function home(RoomRepository $roomRepository)
    {
        // Récupérer les 5 derniers spa de ma bdd
        return $this->render('default/home.html.twig',[
            'rooms'=>$roomRepository->findAll()

        ]);

    }

    #[Route('/reserver', name: 'default_reserver')]
    public function reserver()
    {
        return $this->render('default/reserver.html.twig');
    }
    #[Route('/a-propos')]
    public function aboutUs()
    {
        return $this->render('default/about-us.html.twig');
    }

    #[Route('/contact')]
    public function contact()
    {
        return $this->render('default/contact.html.twig');
    }

    #[Route('/confidentialite')]
    public function confidentiality()
    {
        return $this->render('default/confidentiality.html.twig');
    }

}