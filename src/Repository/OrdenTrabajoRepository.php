<?php

namespace App\Repository;

use App\Entity\OrdenTrabajo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrdenTrabajo|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrdenTrabajo|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrdenTrabajo[]    findAll()
 * @method OrdenTrabajo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdenTrabajoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrdenTrabajo::class);
    }

    public function findBySucursalDeCliente( $sucursalesDeCliente, $ultimo)
    {
        return $this->createQueryBuilder('oa')
            ->andWhere('oa.SucursalDeCliente = :sucursalesDeCliente')
            ->andWhere('oa.id < :ultimo')
            ->andWhere('oa.fecha <= CURRENT_DATE()')
            ->setParameter('sucursalesDeCliente', $sucursalesDeCliente)
            ->setParameter('ultimo', $ultimo)
            ->orderBy('oa.fecha', 'DESC')
            ->addOrderBy('oa.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            
        ;
    }
    public function findByFacility( $facility, $ultimo)
    {
        return $this->createQueryBuilder('oa')
            ->andWhere('oa.Facility = :facility')
            ->andWhere('oa.id < :ultimo')
            ->andWhere('oa.fecha <= CURRENT_DATE()')
            ->setParameter('facility', $facility)
            ->setParameter('ultimo', $ultimo)
            ->orderBy('oa.fecha', 'DESC')
            ->addOrderBy('oa.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            
        ;
    }

    public function findByUser($userId)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.user = :userId')
            ->andWhere('o.fecha <= CURRENT_DATE()')
            ->setParameter('userId', $userId)
            ->orderBy('o.fecha', 'DESC')
            ->addOrderBy('o.id', 'DESC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }
    public function findBySucursalUser($userId)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.user = :userId')
            ->andWhere('o.fecha <= CURRENT_DATE()')
            ->setParameter('userId', $userId)
            ->orderBy('o.fecha', 'DESC')
            ->addOrderBy('o.id', 'DESC')
            ->setMaxResults(20)
            ->groupBy('o.SucursalDeCliente')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByUserEstado($userId, $estados)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.user = :userId')
            ->andWhere('o.estado in (:estados)')
            ->andWhere('o.fecha <= CURRENT_DATE()')
            ->setParameter('userId', $userId)
            ->setParameter('estados', $estados)
            ->orderBy('o.fecha', 'DESC')
            ->addOrderBy('o.id', 'DESC')
            ->addOrderBy('o.estado', 'DESC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }



    public function findDashboard($idCliente = null , $fechaDesde = null , $fechaHasta = null)
    {
        $query = $this->createQueryBuilder('o')
            ->select('
                o AS ot,
                COUNT(o.estado) AS estadoTotal
            ')
            ->groupBy('o.estado');
        if ($idCliente && $idCliente != 'null') {
            $query->join('o.cliente', 'c', 'with', 'c.id = :idCliente');
            $query->setParameter('idCliente', $idCliente);
        }

        if ($fechaDesde && $fechaDesde != 'null') {
            $query->where('o.fecha >= :fechaDesde ');
            $query->setParameter('fechaDesde', $fechaDesde);
        }

        if ($fechaHasta && $fechaHasta != 'null') {
            if ($fechaDesde && $fechaDesde != 'null') {
                $query->andWhere('o.fecha <= :fechaHasta ');
            }else{
                $query->where('o.fecha <= :fechaHasta ');
            }
            $query->setParameter('fechaHasta', $fechaHasta);
        }


        return $query->getQuery()->getResult();
    }

    public function findCantGestionPorEstados($idCliente = null, $fechaDesde = null , $fechaHasta = null)
    {
        $query = $this->createQueryBuilder('o')
            ->select('
                o AS ot,
                COUNT(o) AS estadoTotal
            ')
            ->groupBy('o.estadoGestion, o.estado');

        if ($idCliente && $idCliente != 'null') {
            $query->join('o.cliente', 'c', 'with', 'c.id = :idCliente');
            $query->setParameter('idCliente', $idCliente);
        }

        if ($fechaDesde && $fechaDesde != 'null') {
            $query->where('o.fecha >= :fechaDesde ');
            $query->setParameter('fechaDesde', $fechaDesde);
        }

        if ($fechaHasta && $fechaHasta != 'null') {
            if ($fechaDesde && $fechaDesde != 'null') {
                $query->andWhere('o.fecha <= :fechaHasta ');
            }else{
                $query->where('o.fecha <= :fechaHasta ');
            }
            $query->setParameter('fechaHasta', $fechaHasta);
        }
        return $query->getQuery()->getResult();
    }

    public function findCantEstadosGestion($idCliente = null, $fechaDesde = null , $fechaHasta = null)
    {
        $query = $this->createQueryBuilder('o')
            ->select('
                o AS ot,
                COUNT(o) AS estadoTotal
            ')
            ->groupBy('o.estadoGestion');
        if ($idCliente && $idCliente != 'null') {
            $query->join('o.cliente', 'c', 'with', 'c.id = :idCliente');
            $query->setParameter('idCliente', $idCliente);
        }

        if ($fechaDesde && $fechaDesde != 'null') {
            $query->where('o.fecha >= :fechaDesde ');
            $query->setParameter('fechaDesde', $fechaDesde);
        }

        if ($fechaHasta && $fechaHasta != 'null') {
            if ($fechaDesde && $fechaDesde != 'null') {
                $query->andWhere('o.fecha <= :fechaHasta ');
            }else{
                $query->where('o.fecha <= :fechaHasta ');
            }
            $query->setParameter('fechaHasta', $fechaHasta);
        }
        return $query->getQuery()->getResult();
    }

    public function findCantEstados($idCliente = null, $fechaDesde = null , $fechaHasta = null)
    {
        $query = $this->createQueryBuilder('o')
            ->select('
                o AS ot,
                COUNT(o) AS estadoTotal
            ')
            ->groupBy('o.estado');
        if ($idCliente && $idCliente != 'null') {
            $query->join('o.cliente', 'c', 'with', 'c.id = :idCliente');
            $query->setParameter('idCliente', $idCliente);
        }

        if ($fechaDesde && $fechaDesde != 'null') {
            $query->where('o.fecha >= :fechaDesde ');
            $query->setParameter('fechaDesde', $fechaDesde);
        }

        if ($fechaHasta && $fechaHasta != 'null') {
            if ($fechaDesde && $fechaDesde != 'null') {
                $query->andWhere('o.fecha <= :fechaHasta ');
            }else{
                $query->where('o.fecha <= :fechaHasta ');
            }
            $query->setParameter('fechaHasta', $fechaHasta);
        }


        return $query->getQuery()->getResult();
    }
    public function findByFormularioExpress($formularioResultadoExpress)
    {
        return $this->createQueryBuilder('oa')
        ->andWhere('oa.formularioResultadoExpress = :formularioResultadoExpress')
        ->andWhere('oa.fecha <= CURRENT_DATE()')
        ->setParameter('formularioResultadoExpress', $formularioResultadoExpress)
        ->orderBy('oa.fecha', 'DESC')
        ->addOrderBy('oa.id', 'DESC')
        ->setMaxResults(100)
        ->getQuery()
        ->getResult()
    ;
    }
    public function findAll()
    {
        return $this->createQueryBuilder('o')
            ->addOrderBy('o.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
//    /**
//     * @return OrdenTrabajo[] Returns an array of OrdenTrabajo objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrdenTrabajo
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
