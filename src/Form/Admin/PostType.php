<?php

namespace App\Form\Admin;

use App\Entity\Post;
use App\FormType\WysiwygType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('file');
        $builder->add('content', WysiwygType::class);
        $builder->add('slug');
        $builder->add('enable');
        $builder->add('refuser');
        $builder->add('refcategory');
        $builder->add('tags');
        unset($options);
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
