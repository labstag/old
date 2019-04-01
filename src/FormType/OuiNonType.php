<?php

namespace App\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OuiNonType extends AbstractType
{
    /**
     * Vonfigure le noueau type de champs.
     *
     * @param OptionsResolver $resolver Voir avec symfony
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'expanded' => TRUE,
                'choices'  => [
                    'Non' => 0,
                    'Oui' => 1,
                ],
                'required' => TRUE,
            ]
        );
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
