<?php

namespace Labstag\Form\Admin\Param;

use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DatatableType extends AbstractTypeLib
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $lang = $this->getFilesLang();
        $builder->add(
            'lang',
            ChoiceType::class,
            ['choices' => $lang]
        );
        $builder->add('pagelist', TextType::class);
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // Configure your form options here
        $resolver->setDefaults(
            []
        );
    }

    private function getFilesLang()
    {
        $tabLang = [];
        $files   = glob('../node_modules/bootstrap-table/dist/locale/*');
        foreach ($files as $file) {
            $code           = 'bootstrap-table-';
            $lang           = substr($file, (strpos($file, $code) + strlen($code)));
            $lang           = substr($lang, 0, strpos($lang, '.'));
            $tabLang[$lang] = $lang;
        }

        return $tabLang;
    }
}
