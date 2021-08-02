<?php

namespace App\Controller\Recycle;

use App\Entity\Item;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use App\Search\AutocompleteItem;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\HttpFoundation\Request;

class RecycleItemController extends EasyAdminController
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
    
    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            'app.autocomplete.item' => AutocompleteItem::class,
        ]);
    }

    // public function createItemEditForm($entity, $entityProperties)
    // {
    //     $form = parent::createEditForm($entity, $entityProperties);
    //     $this->createItemForm($form);

    //     return $form;
    // }

    // public function createItemNewForm($entity, $entityProperties)
    // {
    //     $form = parent::createNewForm($entity, $entityProperties);
    //     $this->createItemForm($form);

    //     return $form;
    // }

    // private function createItemForm($form)
    // {
    //     $form->add('tipo', ChoiceType::class, array(
    //       'choices' => Item::$TIPO_ARRAY,
    //       'attr' => [
    //         'css' => 'col-md-6',
    //         ],
    //   ));
    // }

    protected function updateEntity($entity)
    {
        //actualizo formularios que contengan el item
        foreach ($entity->getPropiedadItems() as $key => $pItems) {
            foreach ($pItems->getModulo()->getPropiedadModulos() as $key2 => $pModulos) {
                $pModulos->getFormulario()->setUpdatedAt(new \DateTime());
            }
        }

        parent::updateEntity($entity);
    }

    protected function autocompleteAction()
    {
        $AutocompleteItem = $this->get('app.autocomplete.item');
        $results = $AutocompleteItem->find(
            $this->request->query->get('entity'),
            $this->request->query->get('query'),
            $this->request->query->get('page', 1)
        );

        return new JsonResponse($results);
    }

    protected function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null)
    {
        $queryBuilder = parent::createListQueryBuilder($entityClass, $sortDirection, $sortField, $dqlFilter);

        $queryBuilder
                ->andWhere('entity.deletedAt is not null');
        return $queryBuilder;
    }
}
