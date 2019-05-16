<?php

namespace Labstag\Form\Admin\Param;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MomentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $lang = $this->getFilesLang();
        $builder->add(
            'lang',
            ChoiceType::class,
            ['choices' => $lang]
        );
        $builder->add('format', TextType::class);
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // Configure your form options here
        $resolver->setDefaults(
            [
            ]
        );
    }

    private function getFilesLang()
    {
        $tabLang = [];
        $files   = glob('../node_modules/moment/locale/*');
        foreach ($files as $file) {
            $pathfile           = pathinfo($file);
            $filename           = $pathfile['filename'];
            $tabLang[$filename] = $filename;
        }

        return $tabLang;
    }
}
