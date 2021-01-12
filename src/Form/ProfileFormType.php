<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sucursal', null, [
                'required' => true,
                'attr' => [
                    'data-widget' => 'select2',
                ],
            ])
            ->add('cliente', ClienteType::class, [
                'label' => false,
            ])
            ;
    }

    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
              'data_class' => User::class,
        ]);
    }
}
