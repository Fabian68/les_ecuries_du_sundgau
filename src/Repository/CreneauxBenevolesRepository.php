<?php

namespace App\Repository;

use App\Entity\CreneauxBenevoles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CreneauxBenevoles|null find($id, $lockMode = null, $lockVersion = null)
 * @method CreneauxBenevoles|null findOneBy(array $criteria, array $orderBy = null)
 * @method CreneauxBenevoles[]    findAll()
 * @method CreneauxBenevoles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CreneauxBenevolesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CreneauxBenevoles::class);
    }

    // /**
    //  * @return CreneauxBenevoles[] Returns an array of CreneauxBenevoles objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CreneauxBenevoles
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
