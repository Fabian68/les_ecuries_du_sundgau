<?php

namespace App\Repository;

use App\Entity\Galops;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Galops|null find($id, $lockMode = null, $lockVersion = null)
 * @method Galops|null findOneBy(array $criteria, array $orderBy = null)
 * @method Galops[]    findAll()
 * @method Galops[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GalopsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Galops::class);
    }

    // /**
    //  * @return Galops[] Returns an array of Galops objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Galops
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
