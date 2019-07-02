<?php

namespace App\Repository;

use App\Entity\FilesPdf;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FilesPdf|null find($id, $lockMode = null, $lockVersion = null)
 * @method FilesPdf|null findOneBy(array $criteria, array $orderBy = null)
 * @method FilesPdf[]    findAll()
 * @method FilesPdf[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilesPdfRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FilesPdf::class);
    }

    // /**
    //  * @return FilesPdf[] Returns an array of FilesPdf objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FilesPdf
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
