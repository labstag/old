<?php

namespace Labstag\Form\Admin\Param;

use Labstag\Lib\AbstractTypeLibAdminParam;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OauthType extends AbstractTypeLibAdminParam
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'activate',
            ChoiceType::class,
            [
                'choices' => [
                    'Non' => '0',
                    'Oui' => '1',
                ],
            ]
        );
        $builder->add('type', TextType::class);
        $builder->add('id', TextType::class, ['required' => false]);
        $builder->add('secret', TextType::class, ['required' => false]);
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configure your form options here
        $resolver->setDefaults(
            []
        );
    }
}
