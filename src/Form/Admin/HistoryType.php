<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\History;
use Labstag\FormType\WysiwygType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HistoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('slug');
        $builder->add('enable');
        $builder->add('end');
        $builder->add('refuser');
        $builder->add('resume', WysiwygType::class);
        $builder->add('submit', SubmitType::class);
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => History::class,
            ]
        );
    }
}
