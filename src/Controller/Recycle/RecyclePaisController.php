<?php

namespace App\Controller\Recycle;

use App\Entity\Pais;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;

class RecyclePaisController extends EasyAdminController
{

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

        $queryBuilder
                ->andWhere('entity.deletedAt is not null');
        return $queryBuilder;
    }
}
