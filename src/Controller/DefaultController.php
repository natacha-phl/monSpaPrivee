<?php

namespace App\Controller;

use App\Data\EquipmentFilter;
use App\Form\EquipmentFilterType;
use App\Form\LocationFilterType;
use App\Repository\RoomRepository;
use App\Repository\SpaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'default_home')]
    public function home(RoomRepository $roomRepository, SpaRepository $spaRepository, Request $request)
    {
        $formEquipment = [];
        $formLocation = $this->createForm(LocationFilterType::class,);
        $formLocation->handleRequest($request);

        if ($formLocation->isSubmitted()) {
            $formData = $formLocation->getData();

            if ($formData['department'] !== null) {

                $spas = $spaRepository->findBy([
                    'department' => $formData['department'],
                ]);
            }

            if ($formData['city'] !== null) {

                $spas = $spaRepository->findBy([
                    'city' => $formData['city'],
                ]);
            }

            if (!empty($spas)) {
                $spaIds = [];

                foreach ($spas as $spa) {
                    $spaIds[] = $spa->getId();
                }

                $rooms = $roomRepository->findBy([
                    'spa' => $spaIds
                ]);

                $finalRooms = [];
                foreach ($formData['equipments'] as $searchEquipment) {
                    foreach ($rooms as $room) {
                        foreach ($room->getEquipment() as $equipment) {
                            if ($equipment == $searchEquipment) {
                                if (!in_array($room, $finalRooms)) {
                                    $finalRooms[] = $room;
                                }
                            }
                        }
                    }
                }
            }

            $data = new EquipmentFilter();
            $formEquipment = $this->createForm(EquipmentFilterType::class, $data); // je mets le $data comme ca quand je vais faire le handle request ca va modidifer les données $data
            $formEquipment->handleRequest($request);

        } else {
            $finalRooms = $roomRepository->findAll();
        }

        return $this->render('default/home.html.twig', [
            'rooms' => $finalRooms,
            'formEquipment' => $formEquipment,
            'formLocation' => $formLocation->createView()

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