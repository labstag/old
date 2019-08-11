<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Post;
use Labstag\Entity\Tags;
use Labstag\FormType\WysiwygType;
use Labstag\Lib\AbstractTypeLib;
use Labstag\Repository\TagsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractTypeLib
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('slug');
        $builder->add('imageFile');
        $builder->add('content', WysiwygType::class);
        $builder->add('enable');
        $builder->add('refuser');
        $builder->add('refcategory');
        $builder->add(
            'tags',
            EntityType::class,
            [
                'class'         => Tags::class,
                'multiple'      => true,
                'query_builder' => function (TagsRepository $repository) {
                    return $repository->findTagsByType('post');
                },
            ]
        );
        $builder->add('submit', SubmitType::class);
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
