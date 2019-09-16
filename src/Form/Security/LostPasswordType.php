<?php

namespace Labstag\Form\Security;

use Labstag\Lib\AbstractTypeLibSecurity;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LostPasswordType extends AbstractTypeLibSecurity
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'username',
            TextType::class,
            [
                'label'    => 'Username',
                'required' => false,
            ]
        );
        $builder->add(
            'email',
            EmailType::class,
            [
                'label'    => 'email',
                'required' => false,
            ]
        );
        $builder->add(
            'submit',
            SubmitType::class,
            ['label' => 'Get password']
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // Configure your form options here
        $resolver->setDefaults(
            ['csrf_token_id' => 'lostpassword']
        );
    }
}
