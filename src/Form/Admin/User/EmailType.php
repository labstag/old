<?php

namespace Labstag\Form\Admin\User;

use Labstag\Entity\Email;
use Labstag\Lib\AbstractTypeLibAdminUser;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailType extends AbstractTypeLibAdminUser
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('adresse');
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // Configure your form options here
        $resolver->setDefaults(
            [
                'data_class' => Email::class
            ]
        );
    }
}
