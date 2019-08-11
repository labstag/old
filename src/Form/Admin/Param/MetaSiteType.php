<?php

namespace Labstag\Form\Admin\Param;

use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MetaSiteType extends AbstractTypeLib
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('theme-color', ColorType::class);
        $builder->add('viewport', TextType::class, ['required' => false]);
        $builder->add('description', TextType::class, ['required' => false]);
        $builder->add('keywords', TextType::class, ['required' => false]);
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // Configure your form options here
        $resolver->setDefaults(
            []
        );
    }
}
