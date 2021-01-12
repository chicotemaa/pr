<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Item;
use App\Entity\Opcion;
use App\Entity\PropiedadItem;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\EasyAdminAutocompleteType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class PropiedadItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('item', EasyAdminAutocompleteType::class, [
                'class' => Item::class,
                'required' => true,
                'attr' => [
                  'class' => 'input-sm',
                ],
            ])
            ->add('opcion', EntityType::class, [
                'class' => Opcion::class,
                'attr' => [
                  'class' => 'input-sm',
                  'data-widget' => 'select2',
                ],
                'choices' => [],
            ])
            ->add('orden', null, [
              'attr' => [
                'class' => 'input-sm',
              ],
            ])
            ->add('ancho', ChoiceType::class, [
              'choices' => array(
                  '25 %' => 25,
                  '50 %' => 50,
                  '75 %' => 75,
                  '100 %' => 100,
              ),
              'attr' => [
                'class' => 'input-sm',
              ],
            ])
            ->add('requerido', null, [
              'attr' => [
                'class' => 'input-sm',
              ],
            ])
            ->add('cantidadMinima', null, [
              'attr' => [
                'class' => 'input-sm cantidadMinima',
                'style' => 'visibility: hidden;'
              ],
              'label_attr' => [
                'class' => 'labelCantidadMinima',
                'style' => 'visibility: hidden;'
              ],
            ])
            ->add('isCollection', null, [
              'label' => 'Permite agregar',
              'attr' => [
                'class' => 'input-sm isCollection',
              ],
            ])
            ->addEventListener(
              FormEvents::PRE_SET_DATA,
              array($this, 'onPreSetData')
            )
            ->addEventListener(
              FormEvents::PRE_SUBMIT,
              array($this, 'onPreSubimit')
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
              ->add('opcion', EntityType::class, [
                'class' => Opcion::class,
                'attr' => [
                  'class' => 'input-sm',
                  'data-widget' => 'select2',
                ],
                'choices' => $propiedadItem->getItem()->getOpciones(),
                'required' => false,
              ])
          ;
        }
    }

    public function onPreSubimit(FormEvent $event)
    {
        $propiedadItem = $event->getData();
        $form = $event->getForm();

        if (isset($propiedadItem['item']['autocomplete'])) {
            $form
          ->add('opcion', EntityType::class, [
            'class' => Opcion::class,
            'attr' => [
              'class' => 'input-sm',
              'data-widget' => 'select2',
            ],
            'query_builder' => function (EntityRepository $er) use ($propiedadItem) {
                return $er->createQueryBuilder('u')
                      ->join('u.item', 'i')
                      ->where('i.id = :idItem')
                      ->setParameter('idItem', $propiedadItem['item']['autocomplete'])
                      ->orderBy('u.nombre', 'ASC');
            },
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
