<?php

namespace Labstag\Form\Admin;

use Labstag\Form\Admin\Param\DatatableType;
use Labstag\Form\Admin\Param\DisclaimerType;
use Labstag\Form\Admin\Param\MetaSiteType;
use Labstag\Form\Admin\Param\MomentType;
use Labstag\Form\Admin\Param\OauthType;
use Labstag\Form\Admin\Param\WysiwygType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('site_title', TextType::class);
        $builder->add('robotstxt', TextareaType::class);
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
        $builder->add(
            'meta',
            CollectionType::class,
            [
                'allow_add'    => false,
                'allow_delete' => false,
                'entry_type'   => MetaSiteType::class,
            ]
        );
        $builder->add(
            'wysiwyg',
            CollectionType::class,
            [
                'allow_add'    => false,
                'allow_delete' => false,
                'entry_type'   => WysiwygType::class,
            ]
        );
        $builder->add(
            'disclaimer',
            CollectionType::class,
            [
                'allow_add'    => false,
                'allow_delete' => false,
                'entry_type'   => DisclaimerType::class,
            ]
        );
        $builder->add('submit', SubmitType::class);
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // Configure your form options here
        $resolver->setDefaults(
            [
            ]
        );
    }
}
