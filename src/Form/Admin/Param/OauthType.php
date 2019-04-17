<?php

namespace Labstag\Form\Admin\Param;

use Symfony\Component\Form\AbstractType;
use Labstag\Form\Param\Oauth\GenericType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class OauthType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('activate');
        $builder->add(
            'type',
            ChoiceType::class,
            [
                'choices' => [
                    'bitbucket' => 'bitbucket',
                    'discord'   => 'discord',
                    'github'    => 'github',
                    'gitlab'    => 'gitlab',
                    'google'    => 'google',
                ]
            ]
        );
        $builder->add('key', TextType::class);
        $builder->add('secret', TextType::class);
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
