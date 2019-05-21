<?php

namespace Labstag\Form\Admin;

use Labstag\FormType\WysiwygType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormbuilderViewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['data'] as $field) {
            $this->addField($field, $builder);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            []
        );
    }

    private function addText($field, $builder)
    {
        if ('text' != $field['type']) {
            return;
        }

        switch ($field['subtype']) {
            case 'text':
                $this->addType($field, TextType::class, $builder);

                break;
            case 'password':
                $this->addType($field, PasswordType::class, $builder);

                break;
            case 'email':
                $this->addType($field, EmailType::class, $builder);

                break;
            case 'color':
                $this->addType($field, ColorType::class, $builder);

                break;
            case 'tel':
                $this->addType($field, TelType::class, $builder);

                break;
        }
    }

    private function setPlaceholder(&$data, $field)
    {
        if (isset($field['placeholder'])) {
            $data['placeholder'] = $field['placeholder'];
        }
    }

    private function setClass(&$data, $field)
    {
        $data['attr']['class'] = '';
        if (isset($field['className']) && ('textarea' != $field['type'] || ('textarea' == $field['type'] && 'textarea' == $field['subtype']))) {
            $data['attr']['class'] = $field['className'];
        }
    }

    private function setWidget(&$data, $field)
    {
        if ('date' == $field['type']) {
            $data['widget'] = 'single_text';
        }
    }

    private function setHelp(&$data, $field)
    {
        if (isset($field['description']) && 'button' != $field['type']) {
            $data['help'] = $field['description'];
        }
    }

    private function addButton($field, $builder)
    {
        if ('button' != $field['type']) {
            return;
        }

        switch ($field['subtype']) {
            case 'button':
                $this->addType($field, ButtonType::class, $builder);

                break;
            case 'submit':
                $this->addType($field, SubmitType::class, $builder);

                break;
            case 'reset':
                $this->addType($field, ResetType::class, $builder);

                break;
        }
    }

    private function addTextarea($field, $builder)
    {
        if ('textarea' != $field['type']) {
            return;
        }

        switch ($field['subtype']) {
            case 'textarea':
                $this->addType($field, TextareaType::class, $builder);

                break;
            case 'tinymce':
            case 'quill':
                $this->addType($field, WysiwygType::class, $builder);

                break;
        }
    }

    private function addAutocomplete($field, $builder)
    {
        if ('autocomplete' != $field['type']) {
            return;
        }

        $this->addType($field, TextType::class, $builder);
    }

    private function addCheckboxGroup($field, $builder)
    {
        if ('checkbox-group' != $field['type']) {
            return;
        }

        $this->addType($field, ChoiceType::class, $builder);
    }

    private function addDate($field, $builder)
    {
        if ('date' != $field['type']) {
            return;
        }

        $this->addType($field, DateType::class, $builder);
    }

    private function addFile($field, $builder)
    {
        if ('file' != $field['type']) {
            return;
        }

        $this->addType($field, FileType::class, $builder);
    }

    private function addHeader($field, $builder)
    {
        if ('header' != $field['type']) {
            return;
        }

        $this->addType($field, TextType::class, $builder);
    }

    private function addHidden($field, $builder)
    {
        if ('hidden' != $field['type']) {
            return;
        }

        $this->addType($field, HiddenType::class, $builder);
    }

    private function addNumber($field, $builder)
    {
        if ('number' != $field['type']) {
            return;
        }

        $this->addType($field, IntegerType::class, $builder);
    }

    private function addParagraph($field, $builder)
    {
        if ('paragraph' != $field['type']) {
            return;
        }

        $this->addType($field, TextType::class, $builder);
    }

    private function addRadioGroup($field, $builder)
    {
        if ('radio-group' != $field['type']) {
            return;
        }

        $this->addType($field, ChoiceType::class, $builder);
    }

    private function addSelect($field, $builder)
    {
        if ('select' != $field['type']) {
            return;
        }

        $this->addType($field, ChoiceType::class, $builder);
    }

    private function addField($field, $builder)
    {
        $this->addAutocomplete($field, $builder);
        $this->addCheckboxGroup($field, $builder);
        $this->addDate($field, $builder);
        $this->addText($field, $builder);
        $this->addFile($field, $builder);
        $this->addTextarea($field, $builder);
        $this->addButton($field, $builder);
        $this->addHeader($field, $builder);
        $this->addHidden($field, $builder);
        $this->addNumber($field, $builder);
        $this->addParagraph($field, $builder);
        $this->addRadioGroup($field, $builder);
        $this->addSelect($field, $builder);
    }

    private function setButton(&$data, $field)
    {
        if ('button' == $field['type']) {
            unset($data['required']);
        }
    }

    private function setChoice(&$data, $field)
    {
        if ('checkbox-group' == $field['type'] || 'radio-group' == $field['type']) {
            $choices = [];
            foreach ($field['values'] as $row) {
                $label           = $row['label'];
                $value           = $row['value'];
                $choices[$label] = $value;
            }

            $data['choices']  = $choices;
            $data['expanded'] = true;
            if (isset($field['inline'])) {
                $data['attr']['class'] .= 'form-check-inline';
            }

            $data['placeholder'] = false;
        }

        if ('radio-group' == $field['type']) {
            $data['multiple'] = true;
        }
    }

    private function addType($field, $type, $builder)
    {
        if (!isset($field['name'], $field['label'])) {
            return;
        }

        $data = [
            'label'    => $field['label'],
            'required' => isset($field['required']),
        ];
        $this->setClass($data, $field);
        $this->setWidget($data, $field);
        $this->setHelp($data, $field);
        $this->setPlaceholder($data, $field);
        $this->setButton($data, $field);
        $this->setChoice($data, $field);
        $builder->add($field['name'], $type, $data);
    }
}
