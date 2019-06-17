<?php

namespace App\Repository;

use App\Entity\DatesEvenements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DatesEvenements|null find($id, $lockMode = null, $lockVersion = null)
 * @method DatesEvenements|null findOneBy(array $criteria, array $orderBy = null)
 * @method DatesEvenements[]    findAll()
 * @method DatesEvenements[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DatesEvenementsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DatesEvenements::class);
    }

    // /**
    //  * @return DatesEvenements[] Returns an array of DatesEvenements objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DatesEvenements
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
