<?php

namespace Labstag\Form\Front;

use Labstag\Lib\AbstractTypeLibFront;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DisclaimerType extends AbstractTypeLibFront
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'confirm',
            CheckboxType::class,
            [
                'label'    => "j'ai lu l'avis et en comprends l'énoncé",
                'required' => false,
            ]
        );
        $builder->add(
            'submit',
            SubmitType::class,
            ['label' => 'Je désire accéder au site']
        );
        $builder->add(
            'reset',
            ResetType::class,
            ['label' => 'Je désire quitter dès maintenant']
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // Configure your form options here
        $resolver->setDefaults(
            ['csrf_token_id' => 'login']
        );
    }
}
