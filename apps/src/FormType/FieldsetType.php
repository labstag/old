<?php

namespace Labstag\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldsetType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'legend'       => '',
                'inherit_data' => true,
                'options'      => [],
                'fields'       => [],
                'label'        => false,
            ]
        );
        $resolver->addAllowedTypes(
            'fields',
            ['callable']
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!empty($options['fields']) && is_callable($options['fields'])) {
            $options['fields']($builder, $options);
        }
    }

    public function buildView(
        FormView $view,
        FormInterface $form,
        array $options
    ): void
    {
        if (false !== $options['legend']) {
            $view->vars['legend'] = $options['legend'];
        }

        unset($form);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'fieldset';
    }
}
