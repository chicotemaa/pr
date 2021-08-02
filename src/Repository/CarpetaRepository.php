<?php

namespace App\Repository;

use App\Entity\Carpeta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Carpeta|null find($id, $lockMode = null, $lockVersion = null)
 * @method Carpeta|null findOneBy(array $criteria, array $orderBy = null)
 * @method Carpeta[]    findAll()
 * @method Carpeta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarpetaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Carpeta::class);
    }

    // /**
    //  * @return Carpeta[] Returns an array of Carpeta objects
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
    public function findOneBySomeField($value): ?Carpeta
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
