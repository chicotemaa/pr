<?php

namespace App\Repository;

use App\Entity\Cliente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cliente|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cliente|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cliente[]    findAll()
 * @method Cliente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClienteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cliente::class);
    }

//    /**
//     * @return Cliente[] Returns an array of Cliente objects
//     */
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
    public function findOneBySomeField($value): ?Cliente
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByUserNullLike($term)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.user', 'u')
            ->where('u.id is null')
            ->andWhere('(c.razonSocial like :term or c.apellido like :term or c.nombre like :term)')
            ->setParameter('term', '%'.$term.'%')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByText($texto)
    {
        return $this->createQueryBuilder('c')
            ->where('(c.razonSocial like :texto or c.apellido like :texto or c.nombre like :texto)')
            ->setParameter('texto', '%'.$texto.'%')
            ->getQuery()
            ->getResult()
        ;
    }
}
