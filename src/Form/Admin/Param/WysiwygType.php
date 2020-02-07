<?php

namespace Labstag\Form\Admin\Param;

use Labstag\Lib\AbstractTypeLibAdminParam;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WysiwygType extends AbstractTypeLibAdminParam
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $lang = $this->getFilesLang();
        $builder->add(
            'lang',
            ChoiceType::class,
            ['choices' => $lang]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configure your form options here
        $resolver->setDefaults(
            []
        );
    }

    private function getFilesLang(): array
    {
        $tabLang = [];
        /** @var array $files */
        $files = glob('../node_modules/tinymce-i18n/langs/*');
        foreach ($files as $file) {
            $pathfile           = pathinfo($file);
            $filename           = $pathfile['filename'];
            $tabLang[$filename] = $filename;
        }

        return $tabLang;
    }
}
