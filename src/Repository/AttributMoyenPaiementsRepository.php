<?php

namespace App\Repository;

use App\Entity\AttributMoyenPaiements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AttributMoyenPaiements|null find($id, $lockMode = null, $lockVersion = null)
 * @method AttributMoyenPaiements|null findOneBy(array $criteria, array $orderBy = null)
 * @method AttributMoyenPaiements[]    findAll()
 * @method AttributMoyenPaiements[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttributMoyenPaiementsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AttributMoyenPaiements::class);
    }

    // /**
    //  * @return AttributMoyenPaiements[] Returns an array of AttributMoyenPaiements objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AttributMoyenPaiements
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
