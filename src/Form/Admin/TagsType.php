<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Tags;
use Labstag\Lib\AbstractTypeLibAdmin;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagsType extends AbstractTypeLibAdmin
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('slug');
        $builder->add('submit', SubmitType::class);
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Tags::class,
            ]
        );
    }
}
