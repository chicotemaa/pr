<?php

namespace App\Controller\Recycle;

use App\Entity\Modulo;
use App\Form\ModuloDependenciasType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\HttpFoundation\Request;

class RecycleModuloController extends EasyAdminController
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
    
    protected function persistEntity($entity)
    {
        //seteo valores por defecto si esta null
        foreach ($entity->getPropiedadItems() as $key => $propiedadItem) {
            if (is_null($propiedadItem->getOrden())) {
                $propiedadItem->setOrden(1);
            }

            if (is_null($propiedadItem->getAncho())) {
                $propiedadItem->setAncho(12);
            }
        }

        parent::persistEntity($entity);
    }

    protected function updateEntity($entity)
    {
        //seteo valores por defecto si esta null
        foreach ($entity->getPropiedadItems() as $key => $propiedadItem) {
            if (is_null($propiedadItem->getOrden())) {
                $propiedadItem->setOrden(1);
            }

            if (is_null($propiedadItem->getAncho())) {
                $propiedadItem->setAncho(12);
            }
        }

        //actualizo formularios que contengan el modulo
        foreach ($entity->getPropiedadModulos() as $key2 => $pModulos) {
            $pModulos->getFormulario()->setUpdatedAt(new \DateTime());
        }

        parent::updateEntity($entity);
    }

    public function agregarDependenciasAction()
    {
        $entity = $this->em->getRepository(Modulo::class)->find($this->request->get('id'));

        $form = $this->createForm(ModuloDependenciasType::class, $entity);

        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            parent::updateEntity($entity);

            return $this->redirectToReferrer();
        }

        return $this->render('Modulo/dependencias.html.twig', [
            'form' => $form->createView(),
            'entity' => $entity,
        ]);
    }

    protected function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null)
    {
        $queryBuilder = parent::createListQueryBuilder($entityClass, $sortDirection, $sortField, $dqlFilter);

        $queryBuilder
                ->andWhere('entity.deletedAt is not null');
        return $queryBuilder;
    }
}
