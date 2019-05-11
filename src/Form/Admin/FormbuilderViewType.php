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

    private function addField($field, $builder)
    {
        switch ($field['type']) {
            case 'autocomplete':
                $this->addType($field, TextType::class, $builder);

                break;
            case 'button':
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
                break;
            case 'checkbox-group':
                $this->addType($field, ChoiceType::class, $builder);

                break;
            case 'date':
                $this->addType($field, DateType::class, $builder);

                break;
            case 'file':
                $this->addType($field, FileType::class, $builder);

                break;
            case 'header':
                $this->addType($field, TextType::class, $builder);

                break;
            case 'hidden':
                $this->addType($field, TextType::class, $builder);

                break;
            case 'number':
                $this->addType($field, IntegerType::class, $builder);

                break;
            case 'paragraph':
                $this->addType($field, TextType::class, $builder);

                break;
            case 'radio-group':
                $this->addType($field, ChoiceType::class, $builder);

                break;
            case 'select':
                $this->addType($field, ChoiceType::class, $builder);

                break;
            case 'text':
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
                break;
            case 'textarea':
                switch ($field['subtype']) {
                    case 'textarea':
                        $this->addType($field, TextareaType::class, $builder);

                        break;
                    case 'tinymce':
                    case 'quill':
                        $this->addType($field, WysiwygType::class, $builder);

                        break;
                }
                break;
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

        if (isset($field['className']) && ('textarea' != $field['type'] || ('textarea' == $field['type'] && 'textarea' == $field['subtype']))) {
            $data['attr']['class'] = $field['className'];
        }

        if ('date' == $field['type']) {
            $data['widget'] = 'single_text';
        }

        if (isset($field['description']) && 'button' != $field['type']) {
            $data['help'] = $field['description'];
        }

        if (isset($field['placeholder'])) {
            $data['placeholder'] = $field['placeholder'];
        }

        if ('button' == $field['type']) {
            unset($data['required']);
        }

        if ('checkbox-group' == $field['type'] || 'radio-group' == $field['type']) {
            $choices = [];
            foreach ($field['values'] as $key => $row) {
                $label           = $row['label'];
                $value           = $row['value'];
                $choices[$label] = $value;
            }

            $data['choices']  = $choices;
            $data['expanded'] = true;
        }

        $builder->add($field['name'], $type, $data);
    }
}
