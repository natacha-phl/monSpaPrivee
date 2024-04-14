<?php

namespace App\Repository;

use App\Data\EquipmentFilter;
use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Room>
 *
 * @method Room|null find($id, $lockMode = null, $lockVersion = null)
 * @method Room|null findOneBy(array $criteria, array $orderBy = null)
 * @method Room[]    findAll()
 * @method Room[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

        /**
      * Recupère les produits en lien avec la recherche (filtre)
         * @return Room[]

    */

    public function findWithEquipmentFilter(EquipmentFilter $search):array
    {
        $query = $this
            ->createQueryBuilder('r')
            ->select('e','r')

        ->join('r.equipment', 'e');

        if(!empty($search->equipments)){
            $query = $query
                ->andWhere('e.id IN(:equipments)')
                ->setParameter('equipments', $search->equipments);


        }
        return $query->getQuery()->getResult();


    }




}
