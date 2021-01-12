<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Cliente;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ClienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('condicionIVA', ChoiceType::class, [
                'choices' => [
                    'Consumidor Final' => 3,
                    'Exento' => 5,
                    'Exterior' => 6,
                    'IVA No Alcanzado' => 7,
                    'Monotributista' => 4,
                    'Responsable Inscripto' => 1,
                ],
                'attr' => [
                    'data-widget' => 'select2',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
              'data_class' => Cliente::class,
        ]);
    }
}
