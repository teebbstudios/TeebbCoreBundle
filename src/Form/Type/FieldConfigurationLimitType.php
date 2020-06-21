<?php


namespace Teebb\CoreBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

/**
 * 字段设置数量限制Type
 */
class FieldConfigurationLimitType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if ($options['default_value']) {
            $view->vars['default_value'] = $options['default_value'];
        }
        if ($options['unlimited_value']) {
            $view->vars['unlimited_value'] = $options['unlimited_value'];
        }
        if ($options['min']) {
            $view->vars['min'] = $options['min'];
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'default_value' => 1,
            'unlimited_value' => 0,
            'min' => 0,
            'attr' => [
                'class' => 'form-control form-control-sm input-field-limit'
            ],
            'constraints'=>[
                new NotNull(),
                new PositiveOrZero()
            ],
            'help' => 'teebb.core.fields.configuration.limit_help'
        ]);

    }

    public function getBlockPrefix()
    {
        return 'teebb_field_limit';
    }

    public function getParent()
    {
        return NumberType::class;
    }

}