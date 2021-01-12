<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Modulo;
use App\Entity\PropiedadModulo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PropiedadModuloType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pagina', null, [
              'label' => false,
              'attr' => [
                'class' => 'input-sm',
              ],
            ])
            ->add('paginaNombre', null, [
              'label' => false,
              'attr' => [
                'class' => 'input-sm',
              ],
            ])
            ->add('orden', null, [
              'label' => false,
              'attr' => [
                'class' => 'input-sm',
              ],
            ])
            ->add('modulo', EntityType::class, [
                'class' => Modulo::class,
                'label' => false,
                'required' => false,
                'attr' => [
                  'class' => 'input-sm',
                  'data-widget' => 'select2',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          'data_class' => PropiedadModulo::class,
        ]);
    }
}
