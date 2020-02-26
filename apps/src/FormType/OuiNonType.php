<?php

namespace Labstag\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OuiNonType extends AbstractType
{
    /**
     * Vonfigure le noueau type de champs.
     *
     * @param OptionsResolver $resolver Voir avec symfony
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'expanded' => true,
                'choices'  => [
                    'Non' => 0,
                    'Oui' => 1,
                ],
                'required' => true,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
