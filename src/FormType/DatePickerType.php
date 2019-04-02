<?php
namespace App\FormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
class DatePickerType extends AbstractType
{
    /**
     * @param FormView      $view    view
     * @param FormInterface $form    formulaire
     * @param array         $options data de configureOptions();
     *
     * @return void
     */
    public function buildView(
        FormView $view,
        FormInterface $form,
        array $options
    ): void
    {
        $attr = $options['attr'];
        if (!isset($attr['class'])) {
            $attr['class'] = '';
        }

        $attr['class']      = trim($attr['class'] . ' datepicker');
        $view->vars['attr'] = $attr;
        unset($form);
    }
    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getParent(): string
    {
        return TextType::class;
    }
}
