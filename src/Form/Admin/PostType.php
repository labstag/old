<?php

namespace App\Form\Admin;

use App\Entity\Post;
use App\FormType\FieldsetType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'test',
            FieldsetType::class,
            [
                'legend'     => 'Edit post',
                'data_class' => Post::class,
                'fields'     => function (FormBuilderInterface $builder, array $options) {
                    $builder->add('name');
                    $builder->add('imageFile', VichImageType::class);
                    $builder->add('content');
                    $builder->add('slug');
                    $builder->add('enable');
                    $builder->add('createdAt');
                    $builder->add('updatedAt');
                    $builder->add('refuser');
                    $builder->add('refcategory');
                    $builder->add('tags');
                },
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Post::class,
            ]
        );
    }
}
