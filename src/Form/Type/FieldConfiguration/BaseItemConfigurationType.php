<?php


namespace Teebb\CoreBundle\Form\Type\FieldConfiguration;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Teebb\CoreBundle\Form\Type\FieldConfigurationLimitType;

class BaseItemConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextareaType::class, [
                'label' => 'teebb.core.fields.configuration.description',
                'attr' => [
                    'class' => 'form-control-sm'
                ],
                'help' => 'teebb.core.fields.configuration.description_help',
                'required' => false
            ])
            ->add('required', CheckboxType::class, [
                'label' => 'teebb.core.fields.configuration.require',
                'required' => false
            ])
            ->add('limit', FieldConfigurationLimitType::class, [
                'label' => 'teebb.core.fields.configuration.limit',
            ]);
    }

    /**
     * 构建数值类型字段通用部分表单
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    protected function buildNumericFieldsForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('min', NumberType::class, [
                'label' => 'teebb.core.fields.configuration.min',
                'attr' => [
                    'class' => 'col-12 col-sm-6 form-control-sm',
                ],
                'required' => false,
                'help' => 'teebb.core.fields.configuration.min_help'
            ])
            ->add('max', NumberType::class, [
                'label' => 'teebb.core.fields.configuration.max',
                'attr' => [
                    'class' => 'col-12 col-sm-6 form-control-sm',
                ],
                'required' => false,
                'help' => 'teebb.core.fields.configuration.max_help'
            ])
            ->add('prefix', TextType::class, [
                'label' => 'teebb.core.fields.configuration.prefix',
                'attr' => [
                    'class' => 'col-12 col-sm-6 form-control-sm',
                ],
                'required' => false,
                'help' => 'teebb.core.fields.configuration.prefix_help'
            ])
            ->add('suffix', TextType::class, [
                'label' => 'teebb.core.fields.configuration.suffix',
                'attr' => [
                    'class' => 'col-12 col-sm-6 form-control-sm',
                ],
                'required' => false,
                'help' => 'teebb.core.fields.configuration.suffix_help'
            ]);
    }
}