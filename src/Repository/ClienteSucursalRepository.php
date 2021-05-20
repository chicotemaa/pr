<?php

namespace App\Repository;

use App\Entity\ClienteSucursal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ClienteSucursal|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClienteSucursal|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClienteSucursal[]    findAll()
 * @method ClienteSucursal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClienteSucursalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClienteSucursal::class);
    }

    // /**
    //  * @return ClienteSucursal[] Returns an array of ClienteSucursal objects
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
    public function findOneBySomeField($value): ?ClienteSucursal
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
