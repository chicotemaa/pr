<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Item;
use App\Entity\Opcion;
use App\Entity\PropiedadItem;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityRepository;

class PropiedadItemDependenciaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('item', EntityType::class, [
            'label' => 'Depende de item',
            'class' => Item::class,
            'attr' => [
              'class' => 'input-sm',
              'data-widget' => 'select2',
            ],
          ])
          ->add('dependePropiedadItem', EntityType::class, [
            'label' => 'Depende de item',
            'class' => PropiedadItem::class,
            'attr' => [
              'class' => 'input-sm',
            ],
            'choices' => [],
          ])
          ->add('opcionDepende', EntityType::class, [
            'class' => Opcion::class,
            'required' => false,
            'attr' => [
              'class' => 'input-sm',
            ],
          ])
          ->addEventListener(
            FormEvents::PRE_SET_DATA,
            array($this, 'onPreSetData')
          )
        ;
    }

    public function onPreSetData(FormEvent $event)
    {
        $propiedadItem = $event->getData();
        $form = $event->getForm();
        if (!$propiedadItem) {
            return;
        }
        if ($propiedadItem->getItem()) {
            $form
              ->add('item', EntityType::class, [
                  'class' => Item::class,
                  'attr' => [
                      'data-widget' => 'select2',
                  ],
                  'query_builder' => function (EntityRepository $er) use ($propiedadItem) {
                      return $er->createQueryBuilder('u')
                        ->where('u.id = :item')
                        ->setParameter('item', $propiedadItem->getItem()->getId())
                        ;
                  },
              ])
              ->add('dependePropiedadItem', EntityType::class, [
                'label' => 'Depende de item',
                'class' => PropiedadItem::class,
                'attr' => [
                  'class' => 'input-sm',
                  'data-widget' => 'select2',
                ],
                'choices' => $propiedadItem->getModulo()->getPropiedadItems(),
                'required' => false,
              ])
          ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          'data_class' => PropiedadItem::class,
        ]);
    }
}
