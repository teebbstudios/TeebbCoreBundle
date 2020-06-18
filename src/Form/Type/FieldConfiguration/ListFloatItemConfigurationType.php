<?php


namespace Teebb\CoreBundle\Form\Type\FieldConfiguration;


use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\Configuration\ListFloatItemConfiguration;
use Teebb\CoreBundle\Form\Type\FieldListTypeAllowValuesType;

class ListFloatItemConfigurationType extends BaseItemConfigurationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('allowedValues', FieldListTypeAllowValuesType::class, [
            'label' => 'teebb.core.fields.configuration.list_float_allow_values',
            'help' => 'teebb.core.fields.configuration.list_float_allow_values_help',
            'format' => 'float' //每行键值对的值将格式化为float类型
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ListFloatItemConfiguration::class
        ]);
    }
}