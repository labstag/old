<?php

namespace Labstag\Form\Security;

use Labstag\Lib\AbstractTypeLibSecurity;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractTypeLibSecurity
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'plainPassword',
            RepeatedType::class,
            [
                'type'           => PasswordType::class,
                'label'          => 'password',
                'first_options'  => [
                    'label' => 'Password',
                ],
                'second_options' => [
                    'label' => 'Repeat Password',
                ],
            ]
        );
        $builder->add(
            'submit',
            SubmitType::class,
            ['label' => 'Change password']
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // Configure your form options here
        $resolver->setDefaults(
            [
                'csrf_token_id' => 'changepassword',
            ]
        );
    }
}
