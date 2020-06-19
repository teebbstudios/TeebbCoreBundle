<?php


namespace Teebb\CoreBundle\Form\Type\FieldConfiguration;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Teebb\CoreBundle\Form\Type\FieldConfigurationLimitType;
use Teebb\CoreBundle\Form\Type\FieldConfigurationValueWriteOnceType;
use Teebb\CoreBundle\Form\Type\FieldFileAllowExtType;

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
     * 获取文本类型字段通用部分表单
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    protected function buildStringFieldsForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('length', FieldConfigurationValueWriteOnceType::class, [
            'label' => 'teebb.core.fields.configuration.length',
            'help' => 'teebb.core.fields.configuration.length_help',
            'constraints' => [
                new NotBlank(),
                new GreaterThan(0),
                new LessThan(256)
            ],
            'data' => 255,
            'attr' => [
                'min' => 1,
                'max' => 255,
                'class' => 'col-12 col-sm-6 form-control form-control-sm',
            ]
        ]);
    }

    /**
     * 文件、图片类型字段表单通用设置部分
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    protected function buildFileFieldCommonForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uploadDir', TextType::class, [
                'label' => 'teebb.core.fields.configuration.upload_dir',
                'help' => 'teebb.core.fields.configuration.upload_dir_help',
                'constraints' => [
                    new Length(['min' => 1, 'max' => 255]),
                    new NotBlank(),
                    new Regex('/^(?![-_.])(?!.*?[-_.]$)[a-zA-Z0-9-_.\[\]]+$/')
                ],
                'data' => '[date.Y]-[date.m]',
                'attr' => [
                    'class' => 'col-12 col-sm-6 form-control-sm'
                ]
            ])
            ->add('maxSize', TextType::class, [
                'label' => 'teebb.core.fields.configuration.max_size',
                'help' => 'teebb.core.fields.configuration.max_size_help',
                'help_translation_parameters'=>[
                    '%value%' => ini_get('upload_max_filesize')
                ],
                'required' => false,
                'attr' => [
                    'class' => 'col-12 col-sm-6 form-control-sm'
                ],
                'constraints' => [
                    new Regex('/^\d+(?:|k|M|Mi|Ki)$/')
                ]
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