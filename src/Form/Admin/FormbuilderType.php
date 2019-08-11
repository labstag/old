<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Formbuilder;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormbuilderType extends AbstractTypeLib
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('formbuilder', HiddenType::class);
        $builder->add('slug');
        $builder->add('enable');
        $builder->add('submit', SubmitType::class);
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Formbuilder::class,
            ]
        );
    }
}
