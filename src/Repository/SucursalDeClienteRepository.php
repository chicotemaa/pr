<?php

namespace App\Repository;

use App\Entity\SucursalDeCliente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SucursalDeCliente|null find($id, $lockMode = null, $lockVersion = null)
 * @method SucursalDeCliente|null findOneBy(array $criteria, array $orderBy = null)
 * @method SucursalDeCliente[]    findAll()
 * @method SucursalDeCliente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SucursalDeClienteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SucursalDeCliente::class);
    }

    // /**
    //  * @return SucursalDeCliente[] Returns an array of SucursalDeCliente objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SucursalDeCliente
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
