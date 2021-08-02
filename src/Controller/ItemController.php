<?php

namespace App\Controller;

use App\Entity\Item;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use App\Search\AutocompleteItem;

class ItemController extends EasyAdminController
{
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
}
