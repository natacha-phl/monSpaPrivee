<?php

namespace App\Repository;

use App\Entity\Spa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Spa>
 *
 * @method Spa|null find($id, $lockMode = null, $lockVersion = null)
 * @method Spa|null findOneBy(array $criteria, array $orderBy = null)
 * @method Spa[]    findAll()
 * @method Spa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Spa::class);
    }

    //    /**
    //     * @return Spa[] Returns an array of Spa objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Spa
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


/*    public function findDistinctDepartments(): array
    {
        $departments = $this->createQueryBuilder('s')
            ->select('d.id, d.name')
            ->join('s.department', 'd')
            ->getQuery()
            ->getResult();

        $formattedDepartments = [];
        foreach ($departments as $department) {
            $formattedDepartments[$department['id']] = $department['name'];
        }

        return $formattedDepartments;
    }

    public function findDistinctCities(): array
    {
        $cities = $this->createQueryBuilder('s')
            ->select('c.id, c.name')
            ->join('s.city', 'c')
            ->getQuery()
            ->getResult();

        $formattedCities = [];
        foreach ($cities as $city) {
            $formattedCities[$city['id']] = $city['name'];
        }

        return $formattedCities;
    }*/


    /*public function findDistinctDepartments(): array
    {
        $departments = $this->createQueryBuilder('s')
            ->select('d.name') // Sélectionner seulement le nom
            ->join('s.department', 'd')
            ->groupBy('d.name')
            ->getQuery()
            ->getResult();

        dump($departments);


        // Formatage des résultats
        $formattedDepartments = [];
        foreach ($departments as $department) {
            $formattedDepartments[] = $department['name']; // Ajouter le nom au tableau formaté
        }

        return $formattedDepartments;
    }

    public function findDistinctCities(): array
    {
        $cities = $this->createQueryBuilder('s')
            ->select('c.name') // Sélectionner seulement le nom
            ->join('s.city', 'c')
            ->groupBy('c.name')
            ->getQuery()
            ->getResult();

        // Formatage des résultats
        $formattedCities = [];
        foreach ($cities as $city) {
            $formattedCities[] = $city['name']; // Ajouter le nom au tableau formaté
        }

        return $formattedCities;
    }

*/






}
