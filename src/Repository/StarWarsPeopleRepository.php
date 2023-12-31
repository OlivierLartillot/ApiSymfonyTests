<?php

namespace App\Repository;

use App\Entity\StarWarsPeople;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StarWarsPeople>
 *
 * @method StarWarsPeople|null find($id, $lockMode = null, $lockVersion = null)
 * @method StarWarsPeople|null findOneBy(array $criteria, array $orderBy = null)
 * @method StarWarsPeople[]    findAll()
 * @method StarWarsPeople[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StarWarsPeopleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StarWarsPeople::class);
    }

//    /**
//     * @return StarWarsPeople[] Returns an array of StarWarsPeople objects
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

//    public function findOneBySomeField($value): ?StarWarsPeople
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
