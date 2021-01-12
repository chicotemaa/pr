<?php

namespace App\Repository;

use App\Entity\Solicitud;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Solicitud|null find($id, $lockMode = null, $lockVersion = null)
 * @method Solicitud|null findOneBy(array $criteria, array $orderBy = null)
 * @method Solicitud[]    findAll()
 * @method Solicitud[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolicitudRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Solicitud::class);
    }

    public function finByActivo(ServiceEntityRepository $r, $estado = true)
    {
        return $r->createQueryBuilder('s')
            ->where('s.activo = :val')
            ->setParameter('val', $estado)
            ->orderBy('s.titulo', 'ASC')
        ;
    }

    public function findCantSolEst($idCliente = null, $fechaDesde = null , $fechaHasta = null)
    {
        $query = $this->createQueryBuilder('s')
            ->select('
                s AS se,
                COUNT(s) AS estadoTotal
            ')
            ->groupBy('s.estado');
        if ($idCliente && $idCliente != 'null') {
            $query->join('s.cliente', 'c', 'with', 'c.id = :idCliente');
            $query->setParameter('idCliente', $idCliente);
        }

        if ($fechaDesde && $fechaDesde != 'null') {
            $query->where('s.fechaCompromiso >= :fechaDesde ');
            $query->setParameter('fechaDesde', $fechaDesde);
        }

        if ($fechaHasta && $fechaHasta != 'null') {
            if ($fechaDesde && $fechaDesde != 'null') {
                $query->andWhere('s.fechaCompromiso <= :fechaHasta ');
            }else{
                $query->where('s.fechaCompromiso <= :fechaHasta ');
            }

            $query->setParameter('fechaHasta', $fechaHasta);
        }

        return $query->getQuery()->getResult();
    }

    public function findCantSolServ($idCliente = null, $fechaDesde = null , $fechaHasta = null)
    {
        $query = $this->createQueryBuilder('s')
            ->select('
                s AS se,
                COUNT(s) AS estadoTotal
            ')
            ->groupBy('s.servicio');
        if ($idCliente && $idCliente != 'null') {
            $query->join('s.cliente', 'c', 'with', 'c.id = :idCliente');
            $query->setParameter('idCliente', $idCliente);
        }

        if ($fechaDesde  && $fechaDesde != 'null') {
            $query->where('s.fechaCompromiso >= :fechaDesde ');
            $query->setParameter('fechaDesde', $fechaDesde);
        }

        if ($fechaHasta && $fechaHasta != 'null') {
            if ($fechaDesde && $fechaDesde != 'null') {
                $query->andWhere('s.fechaCompromiso <= :fechaHasta ');
            }else{
                $query->where('s.fechaCompromiso <= :fechaHasta ');
            }

            $query->setParameter('fechaHasta', $fechaHasta);
        }

        return $query->getQuery()->getResult();
    }
}
