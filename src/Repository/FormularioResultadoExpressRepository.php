<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\FormularioResultadoExpress;

/**
 * FormularioResultadoExpressRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FormularioResultadoExpressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormularioResultadoExpress::class);
    }

    public function findCompletadosByUser($idUser)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('a');

        $qb->select('a', 'r', 'uf', 'f')
            ->from('SistemaFormularioBundle:FormularioResultadoExpress', 'a')
            ->leftJoin('a.resultados', 'r')
            ->join('a.usuarioFormulario', 'uf')
            ->join('uf.formulario', 'f')
            ->join('uf.user', 'u')
            ->where('u.id = :user')
            ->setParameter('user', $idUser)
        ;

        return $qb->getQuery()->getResult();
    }

    public function findCompletadosByOT($idFormulario, $idUser)
    {
        return $this->createQueryBuilder('o')
              ->leftJoin('a.resultados', 'r')
              ->join('a.ordenTrabajo', 'uf')
              ->join('uf.formulario', 'f')
              ->join('uf.user', 'u')
              ->where('u.id = :user')
              ->andWhere('uf.id = :formulario')
              ->setParameter('user', $idUser)
              ->setParameter('formulario', $idFormulario)
              ->getQuery()
              ->getResult()
          ;
    }

    public function findCompletadosSegunParametros($filtros)
    {
        //$filtros['userAsociados'] clientes asociados al usuario
        //busco los formularios resultados
        $qb = $this->getEntityManager()->createQueryBuilder('a');
        $arrayKeys = array_keys($filtros['arrayFiltro']);
        $arrayValues = array_values($filtros['arrayFiltro']);
        $parametrosQuery = [];

        $qb->select('a.id')
                ->from('SistemaFormularioBundle:Resultado', 'r')
                ->join('r.formularioResultadoExpress', 'a')
                ->join('r.propiedadItem', 'pi')
                ->join('pi.item', 'it')
                ->where("DATE_FORMAT(a.createdAt,'%Y-%m-%d') BETWEEN :fechaDesde and :fechaHasta")
                ->groupBy('a.id,it.id')
            ;

        $parametrosQuery['fechaDesde'] = $filtros['fechaDesde']->format('Y-m-d');
        $parametrosQuery['fechaHasta'] = $filtros['fechaHasta']->format('Y-m-d');

        if (!empty($filtros['userAsociados'])) {
            $qb
                ->join('a.usuarioFormulario', 'uf')
                ->join('uf.user', 'user')
                ->andWhere('user.id in (:clientes)')
              ;

            $parametrosQuery['clientes'] = $filtros['userAsociados'];
        }

        //me fijo q tipoLugar eligio ciudad, provincia, pais
        if ('ciudad' == $filtros['lugarTipo']) {
            $qb
                ->andWhere('a.ciudad like :ciudad')
                ->andWhere('a.provincia like :provincia')
                ->andWhere('a.pais like :pais')

              ;
            $parametrosQuery['ciudad'] = '%'.$filtros['ciudad'].'%';
            $parametrosQuery['provincia'] = '%'.$filtros['provincia'].'%';
            $parametrosQuery['pais'] = '%'.$filtros['pais'].'%';
        } elseif ('provincia' == $filtros['lugarTipo']) {
            $qb
                ->andWhere('a.provincia like :provincia')
                ->andWhere('a.pais like :pais')
              ;
            $parametrosQuery['provincia'] = '%'.$filtros['provincia'].'%';
            $parametrosQuery['pais'] = '%'.$filtros['pais'].'%';
        } else {
            $qb
                ->andWhere('a.pais like :pais')
              ;
            $parametrosQuery['pais'] = '%'.$filtros['pais'].'%';
        }

        //filtros items
        $filtroItemsArray = [];
        foreach ($filtros['arrayFiltro'] as $key => $filtro) {
            $arrayValues = (is_array($filtro)) ? array_values($filtro) : [$filtro];
            $filtroItemsArray[] = '(it.id = '.$key.' and '.'r.valor in (:valorArray'.$key.'))';
            $parametrosQuery['valorArray'.$key] = $arrayValues;
        }

        $filtroItems = implode(' or ', $filtroItemsArray);
        if (!empty($filtroItems)) {
            $qb
              ->andWhere($filtroItems)

              ;
        }

        $qbResultado = $this->getEntityManager()->createQueryBuilder('f');
        $qbResultado
                ->select('f')
                ->from('SistemaFormularioBundle:FormularioResultadoExpress', 'f')
                ->where($qbResultado->expr()->in('f.id', $qb->getDql()))
                ->setParameters($parametrosQuery)
            ;

        return $qbResultado->getQuery()->getResult();
    }

    public function findArray($formularioResueltos)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('a');

        $qb->select('a')
                  ->from('SistemaFormularioBundle:FormularioResultadoExpress', 'a')
                  ->where('a.id in (:formularioResueltos)')
                  ->setParameter('formularioResueltos', $formularioResueltos)
              ;

        return $qb->getQuery()->getResult();
    }

    public function findByUserEstado($userId, $estados)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.user = :userId')
            ->andWhere('o.estado in (:estados)')
            ->andWhere('o.fecha <= CURRENT_DATE()')
            ->andWhere('o.compraMateriales is null')
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

    public function findByUserEstadoCM($userId, $estados)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.user = :userId')
            ->andWhere('o.estado in (:estados)')
            ->andWhere('o.fecha <= CURRENT_DATE()')
            ->andWhere('o.compraMateriales is not null')
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
}
