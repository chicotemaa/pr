<?php

namespace App\Form\Filter;

use Symfony\Component\Form\FormBuilderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\FilterType;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EstadoFilterType extends FilterType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('estado', ChoiceType::class, [
            'label' => false,
            'choices' => [
                'Pendiente' => 0,
                'Estoy en camino' => 1,
                'Me recibió' => 2,
                'No me atendió' => 3,
                'Finalizado' => 4,
                'Postergado' => 5,
            ],
            'expanded' => true,
            'multiple' => false,
            ]);
    }

    public function filter(QueryBuilder $queryBuilder, FormInterface $form, array $metadata)
    {
        $queryBuilder
            ->andWhere('entity.estado = '.$form->getData()['estado']);
    }
}
