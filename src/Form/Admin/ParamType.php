<?php

namespace Labstag\Form\Admin;

use Labstag\Form\Admin\Param\OauthType;
use Symfony\Component\Form\AbstractType;
use Labstag\Form\Admin\Param\DatatableType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Labstag\Form\Admin\Param\MomentType;

class ParamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('site_title',  TextType::class);
        $builder->add(
            'oauth',
            CollectionType::class,
            [
                'allow_add'    => true,
                'allow_delete' => true,
                'entry_type'   => OauthType::class,
            ]
        );
        $builder->add(
            'datatable',
            CollectionType::class,
            [
                'allow_add'    => false,
                'allow_delete' => false,
                'entry_type'   => DatatableType::class,
            ]
        );
        $builder->add(
            'moment',
            CollectionType::class,
            [
                'allow_add'    => false,
                'allow_delete' => false,
                'entry_type'   => MomentType::class,
            ]
        );
        $builder->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                // Configure your form options here
            ]
        );
    }
}
