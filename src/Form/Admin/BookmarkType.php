<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Bookmark;
use Labstag\Entity\Tags;
use Labstag\FormType\WysiwygType;
use Labstag\Lib\AbstractTypeLibAdmin;
use Labstag\Repository\TagsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class BookmarkType extends AbstractTypeLibAdmin
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('url');
        $builder->add('imageFile', VichImageType::class);
        $builder->add('content', WysiwygType::class);
        $builder->add('enable');
        $builder->add('refuser');
        $builder->add(
            'tags',
            EntityType::class,
            [
                'class'         => Tags::class,
                'multiple'      => true,
                'query_builder' => function (TagsRepository $repository) {
                    return $repository->findTagsByTypeNotTemporary('bookmark');
                },
                'attr'          => [
                    'data-url' => $this->router->generate('admintemporary_tags', ['type' => 'bookmark']),
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
                'data_class' => Bookmark::class,
            ]
        );
    }
}
