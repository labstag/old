<?php

namespace Labstag\Form\Admin\User;

use Labstag\Entity\Email;
use Labstag\Lib\AbstractTypeLibAdminUser;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailType extends AbstractTypeLibAdminUser
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('adresse');
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configure your form options here
        $resolver->setDefaults(
            [
                'data_class' => Email::class,
            ]
        );
    }
}
