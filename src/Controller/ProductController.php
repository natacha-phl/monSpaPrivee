<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\Spa;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    //je le mettrai que si j'ai le temps de faire un résultat en fonction de ce qui est sélectionner dans le filtre
/*    #[Route('/result')]
    public function result(){
        return $this-> render('product/result.html.twig');
    }*/

    #[Route('/room/{id}')]
    public function room(Room $room, RoomRepository $roomRepository)
    {

        //$roomRepository->find()
       return $this->render('product/room.html.twig',[
           'spa'=>$room->getSpa(),
           'room'=>$room


    ]);
    }
}