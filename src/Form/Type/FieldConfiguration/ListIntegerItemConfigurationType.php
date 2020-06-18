<?php


namespace Teebb\CoreBundle\Form\Type\FieldConfiguration;


use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\Configuration\ListIntegerItemConfiguration;
use Teebb\CoreBundle\Form\Type\FieldListTypeAllowValuesType;

class ListIntegerItemConfigurationType extends BaseItemConfigurationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('allowedValues', FieldListTypeAllowValuesType::class, [
            'label' => 'teebb.core.fields.configuration.list_integer_allow_values',
            'help' => 'teebb.core.fields.configuration.list_integer_allow_values_help',
            'format' => 'integer' //每行键值对的值将格式化为integer类型
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ListIntegerItemConfiguration::class
        ]);
    }
}