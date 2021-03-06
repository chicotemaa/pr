<?php

namespace App\Controller\Recycle;

use App\Entity\Solicitud;
use App\Service\MailReader;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\HttpFoundation\Request;

class RecycleSolicitudController extends EasyAdminController
{
    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            'app.read.mail' => MailReader::class,
        ]);
    }

    protected function initialize(Request $request)
    {
        $this->dispatch(EasyAdminEvents::PRE_INITIALIZE);

        $this->config = $this->get('easyadmin.config.manager')->getBackendConfig();

        if (0 === \count($this->config['entities'])) {
            throw new NoEntitiesConfiguredException();
        }

        // this condition happens when accessing the backend homepage and before
        // redirecting to the default page set as the homepage
        if (null === $entityName = $request->query->get('entity')) {
            return;
        }

        if (!\array_key_exists($entityName, $this->config['entities'])) {
            throw new UndefinedEntityException(['entity_name' => $entityName]);
        }

        $this->entity = $this->get('easyadmin.config.manager')->getEntityConfig($entityName);

        $action = $request->query->get('action', 'list');
        if (!$request->query->has('sortField')) {
            $sortField = $this->entity[$action]['sort']['field'] ?? $this->entity['primary_key_field_name'];
            $request->query->set('sortField', $sortField);
        }
        if (!$request->query->has('sortDirection')) {
            $sortDirection = $this->entity[$action]['sort']['direction'] ?? 'DESC';
            $request->query->set('sortDirection', $sortDirection);
        }

        $this->em = $this->getDoctrine()->getManagerForClass($this->entity['class']);
        $this->em->getFilters()->disable('softdeleteable');
        $this->request = $request;

        $this->dispatch(EasyAdminEvents::POST_INITIALIZE);
    }

    protected function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null)
    {
        $queryBuilder = parent::createListQueryBuilder($entityClass, $sortDirection, $sortField, $dqlFilter);

        // para los filtros si hace click en uno de los item del dashboard
        $estado = $this->request->query->get('estado');
        $servicio = $this->request->query->get('servicio');

        if ($estado) {
            $queryBuilder
                ->where('entity.estado = :estado')
                ->setParameter('estado', $estado);
        } elseif (is_numeric($estado)) {
            $queryBuilder
                ->where('entity.estado = :estado')
                ->setParameter('estado', $estado);
        }

        if ($servicio) {
            $queryBuilder
                ->join('entity.servicio', 's')
                ->where('s.id = :servicio')
                ->setParameter('servicio', $servicio);
        }

        $idCliente = $this->request->query->get('idCliente');

        if ($idCliente != 'null' && !empty($idCliente)) {
            $queryBuilder
                ->join('entity.cliente', 'c')
                ->andWhere('c.id = :idCliente')
                ->setParameter('idCliente', $idCliente);
        }

        $fechaDesde = $this->request->query->get('fechaDesde');

        if ($fechaDesde != 'null' && !empty($fechaDesde)) {
            $fechaDesde = \DateTime::createFromFormat('d-m-Y H:i', $fechaDesde.' 00:00' );
            $queryBuilder
                ->andWhere('entity.fechaCompromiso >= :fechaDesde')
                ->setParameter('fechaDesde', $fechaDesde);
        }

        $fechaHasta = $this->request->query->get('fechaHasta');

        if ($fechaHasta != 'null' && !empty($fechaHasta)) {
            $fechaHasta = \DateTime::createFromFormat('d-m-Y H:i', $fechaHasta.' 24:00' );
            $queryBuilder
                ->andWhere('entity.fechaCompromiso <= :fechaHasta')
                ->setParameter('fechaHasta', $fechaHasta);
        }

        $queryBuilder
                ->andWhere('entity.ordenTrabajo IS NULL');
        $queryBuilder
                ->andWhere('entity.deletedAt is not null');
        return $queryBuilder;
    }

    protected function persistEntity($entity)
    {
        if ($this->isGranted('ROLE_CLIENTE') && !$this->isGranted('ROLE_ENCARGADO')) {
            $entity->setCliente($this->getUser()->getCliente());
        }

        parent::persistEntity($entity);
    }

    protected function generarOtAction()
    {
        $solicitud = $this->em->getRepository(Solicitud::class)
            ->find($this->request->query->get('id'));

        if ($solicitud->getOrdenTrabajo()) {
            $this->addFlash('danger', 'La solicitud ya tiene asignada una orden de trabajo');

            return $this->redirectToRoute('easyadmin', array(
                'action' => 'list',
                'entity' => 'Solicitud',
            ));
        } else {
            $this->request->getSession()->set('solicitud_ot', [
                'id' => $solicitud->getId(),
                'fecha' => $solicitud->getCreatedAt()->format('d/m/Y'),
                'cliente' => $solicitud->getCliente(),
                'servicio' => $solicitud->getServicio(),
                'detalle' => $solicitud->getDetalle(),
            ]);

            return $this->redirectToRoute('easyadmin', array(
                'action' => 'new',
                'entity' => 'OrdenTrabajo',
            ));
        }
    }

    protected function removeEntity($entity)
    {
        if ($entity->getOrdenTrabajo()) {
            $this->addFlash('danger', 'La solicitud no se puede eliminar, porque tiene asiganda una orden de trabajo');

            return $this->redirectToRoute('easyadmin', array(
                'action' => 'list',
                'entity' => 'Solicitud',
            ));
        } else {
            parent::removeEntity($entity);
        }
    }

    public function getUnreads()
    {
        $em = $this->getDoctrine()->getManager();
        $unReads = $em->getRepository(Solicitud::class)
        ->findByLeido(null);        
        return $this->render(
            'Solicitud/alert.html.twig',
            ['unreads' => $unReads]
        );
    }

    protected function showAction()
    {
        $this->dispatch(EasyAdminEvents::PRE_SHOW);

        $id = $this->request->query->get('id');
        $easyadmin = $this->request->attributes->get('easyadmin');
        $entity = $easyadmin['item'];

        $fields = $this->entity['show']['fields'];
        $deleteForm = $this->createDeleteForm($this->entity['name'], $id);

        $this->dispatch(EasyAdminEvents::POST_SHOW, [
            'deleteForm' => $deleteForm,
            'fields' => $fields,
            'entity' => $entity,
        ]);

        $parameters = [
            'entity' => $entity,
            'fields' => $fields,
            'delete_form' => $deleteForm->createView(),
        ];

        $entity->setLeido(true);
        parent::updateEntity($entity);

        return $this->executeDynamicMethod('render<EntityName>Template', ['show', $this->entity['templates']['show'], $parameters]);
    }
}
