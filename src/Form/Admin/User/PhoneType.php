<?php

namespace Labstag\Form\Admin\User;

use Labstag\Entity\Phone;
use Labstag\Lib\AbstractTypeLibAdminUser;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhoneType extends AbstractTypeLibAdminUser
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('numero');
        $builder->add('type');
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configure your form options here
        $resolver->setDefaults(
            [
                'data_class' => Phone::class,
            ]
        );
    }
}
