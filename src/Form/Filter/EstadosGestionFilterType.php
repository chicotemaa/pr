<?php

namespace App\Form\Filter;

use Symfony\Component\Form\FormBuilderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\FilterType;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EstadosGestionFilterType extends FilterType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('estadosGestion', ChoiceType::class, [
            'label' => false,
            'choices' => [
                'Abierta' => 0,
                'Pendiente' => 1,
                'Cerrada' => 2,
            ],
            'expanded' => true,
            'multiple' => false,
            ]);
    }

    public function filter(QueryBuilder $queryBuilder, FormInterface $form, array $metadata)
    {
        $queryBuilder
            ->andWhere('entity.estadoGestion = '.$form->getData()['estadosGestion']);
    }
}
