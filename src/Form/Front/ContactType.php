<?php

namespace Labstag\Form\Front;

use Labstag\FormType\WysiwygType;
use Labstag\Lib\AbstractTypeLibFront;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractTypeLibFront
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('sujet', TextType::class);
        $builder->add('email', EmailType::class);
        $builder->add('name', TextType::class);
        $builder->add('content', WysiwygType::class, ['required' => false]);
        $builder->add('submit', SubmitType::class, ['label' => "Envoyer un message à l'administrateur"]);
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
