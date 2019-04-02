<?php

namespace App\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Post;

class FieldsetType extends AbstractType
{
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'legend'       => '',
                'inherit_data' => true,
                'options'      => array(),
                'fields'       => array(),
                'label'        => false,
            ]
        );
        $resolver->addAllowedTypes(
            'fields',
            [
                'callable',
            ]
        );
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!empty($options['fields']) && is_callable($options['fields'])) {
            $options['fields']($builder, $options);
        }
    }

    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function buildView(
        FormView $view,
        FormInterface $form,
        array $options
    )
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
