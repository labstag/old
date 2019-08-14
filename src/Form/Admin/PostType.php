<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Category;
use Labstag\Entity\Post;
use Labstag\Entity\Tags;
use Labstag\FormType\WysiwygType;
use Labstag\Lib\AbstractTypeLib;
use Labstag\Repository\CategoryRepository;
use Labstag\Repository\TagsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PostType extends AbstractTypeLib
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('slug');
        $builder->add('imageFile', VichImageType::class);
        $builder->add('content', WysiwygType::class);
        $builder->add('enable');
        $builder->add('refuser');
        $builder->add(
            'refcategory',
            EntityType::class,
            [
                'class'         => Category::class,
                'multiple'      => true,
                'query_builder' => function (CategoryRepository $repository) {
                    return $repository->findForForm();
                },
                'attr'          => [
                    'data-url' => $this->router->generate('admintemporary_category'),
                ],
            ]
        );
        // $builder->add('refcategory');
        $builder->add(
            'tags',
            EntityType::class,
            [
                'class'         => Tags::class,
                'multiple'      => true,
                'query_builder' => function (TagsRepository $repository) {
                    return $repository->findTagsByTypeNotTemporary('post');
                },
                'attr'          => [
                    'data-url' => $this->router->generate('admintemporary_tags', ['type' => 'post']),
                ],
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
