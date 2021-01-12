<?php

namespace App\Controller;

use App\Entity\Carpeta;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ElementoCarpetaController extends EasyAdminController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    protected function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null)
    {
        $queryBuilder = parent::createListQueryBuilder($entityClass, $sortDirection, $sortField, $dqlFilter);

        $idCarpeta = $this->request->query->get('idCarpeta');
        // obtengo el nombre de la carpeta

        if ($idCarpeta) {
            $carpetaNombre = $this->getDoctrine()->getRepository(Carpeta::class)->find($idCarpeta)->getNombre();
            $this->session->set('carpetaSeleccionada', $idCarpeta);
            $this->session->set('carpetaNombre', $carpetaNombre);
            $queryBuilder
            ->join('entity.cliente', 'c')
            ->join('c.carpeta', 'a', 'with', ' a.id = :idCarpeta')
            ->setParameter('idCarpeta', $idCarpeta);
        } elseif ($this->session->has('carpetaSeleccionada')) {
            $carpetaNombre = $this->getDoctrine()->getRepository(Carpeta::class)->find($this->session->get('carpetaSeleccionada'))->getNombre();
            $this->session->set('carpetaSeleccionada', $this->session->get('carpetaSeleccionada'));
            $queryBuilder
            ->join('entity.cliente', 'c')
            ->join('c.carpeta', 'a', 'with', ' a.id = :idCarpeta')
            ->setParameter('idCarpeta', $this->session->get('carpetaSeleccionada'));
        }

        return $queryBuilder;
    }

    // Creates the Doctrine query builder used to look for items according to the
    // user's query. Override it to filter the elements displayed in the search listing
    protected function createSearchQueryBuilder($entityClass, $searchQuery, array $searchableFields, $sortField = null, $sortDirection = null, $dqlFilter = null)
    {
        $queryBuilder = parent::createListQueryBuilder($entityClass, $sortDirection, $sortField, $dqlFilter);

        $idCarpeta = $this->request->query->get('idCarpeta');
        // obtengo el nombre de la carpeta

        if ($idCarpeta) {
            $carpetaNombre = $this->getDoctrine()->getRepository(Carpeta::class)->find($idCarpeta)->getNombre();
            $this->session->set('carpetaSeleccionada', $idCarpeta);
            $this->session->set('carpetaNombre', $carpetaNombre);
            $queryBuilder
            ->join('entity.cliente', 'c')
            ->join('c.carpeta', 'a', 'with', ' a.id = :idCarpeta')
            ->setParameter('idCarpeta', $idCarpeta);
        } elseif ($this->session->has('carpetaSeleccionada')) {
            $carpetaNombre = $this->getDoctrine()->getRepository(Carpeta::class)->find($this->session->get('carpetaSeleccionada'))->getNombre();
            $this->session->set('carpetaSeleccionada', $this->session->get('carpetaSeleccionada'));
            $queryBuilder
            ->join('entity.cliente', 'c')
            ->join('c.carpeta', 'a', 'with', ' a.id = :idCarpeta')
            ->setParameter('idCarpeta', $this->session->get('carpetaSeleccionada'));
        }

        return $queryBuilder;
    }
}
