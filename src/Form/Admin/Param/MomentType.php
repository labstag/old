<?php

namespace Labstag\Form\Admin\Param;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MomentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $lang = $this->getFilesLang();
        $builder->add(
            'lang',
            ChoiceType::class,
            [
                'choices' => $lang
            ]
        );
        $builder->add('format', TextType::class);
        unset($options);
    }

    private function getFilesLang()
    {
        $tabLang = [];
        $files   = glob('../node_modules/moment/locale/*');
        foreach ($files as $file){
            $pathfile           = pathinfo($file);
            $filename           = $pathfile['filename'];
            $tabLang[$filename] = $filename;
        }

        return $tabLang;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                // Configure your form options here
            ]
        );
    }
}
