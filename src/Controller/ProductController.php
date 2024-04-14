<?php

namespace App\Controller;
use App\Entity\Spa;
use App\Repository\RoomRepository;
use App\Repository\SpaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    //je le mettrai que si j'ai le temps de faire un résultat en fonction de ce qui est sélectionner dans le filtre
/*    #[Route('/result')]
    public function result(){
        return $this-> render('product/result.html.twig');
    }*/

    #[Route('/room/{id}', name: 'product-room')]
    public function room($id, RoomRepository $roomRepository)
    {
        $room = $roomRepository->find($id);
        return $this->render('product/room.html.twig',[
           'room'=>$room,
            'hours1' => 1,
            'hours2'=>2,
            'hours3'=> 3,
    ]);
    }

    #[Route('/spa/{id}')]
    public function spa($id, SpaRepository $spaRepository, RoomRepository $roomRepository)
    {
        $spa = $spaRepository->find($id);
        $rooms = $roomRepository->findBy(['spa'=>$spa]);
        return $this->render('product/spa.html.twig', [
            'spa' => $spa,
            'rooms'=> $rooms,

        ]);
    }

}