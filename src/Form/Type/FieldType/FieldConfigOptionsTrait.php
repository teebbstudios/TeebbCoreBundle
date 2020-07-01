<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Teebb\CoreBundle\Entity\Fields\Configuration\FieldDepartConfigurationInterface;

trait FieldConfigOptionsTrait
{
    public function baseConfigOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => false,
            'row_attr' => [
                'class' => 'form-item-wrapper mb-0'
            ],
        ]);

        $resolver->setDefined('field_configuration');
        $resolver->setDefined('field_service');

        $resolver->setAllowedTypes('field_configuration', 'object');
        $resolver->setAllowedTypes('field_service', 'object');
    }

    /**
     * 配置数值型字段NumberType options
     * @param FieldDepartConfigurationInterface $fieldSettings
     * @param array $fieldOptions
     * @return array
     */
    public function configNumericFieldOptions(FieldDepartConfigurationInterface $fieldSettings, array $fieldOptions)
    {
        $min = $fieldSettings->getMin();
        $max = $fieldSettings->getMax();

        $min ? $fieldOptions['attr']['min'] = $min : $fieldOptions['attr'];
        $max ? $fieldOptions['attr']['max'] = $max : $fieldOptions['attr'];

        $fieldOptions['constraints'] = [];
        if ($fieldSettings->isRequired()) {
            $fieldOptions['constraints'] = [
                new NotBlank(),
            ];
        }

        $min ? array_push($fieldOptions['constraints'], new GreaterThanOrEqual($min)) : $fieldOptions['constraints'];
        $max ? array_push($fieldOptions['constraints'], new LessThanOrEqual($max)) : $fieldOptions['constraints'];

        return $fieldOptions;
    }

    /**
     * 配置listInteger、listFloat类型字段value表单options
     *
     * @param FieldDepartConfigurationInterface $fieldSettings
     * @return array
     */
    public function configListNumericFieldOptions(FieldDepartConfigurationInterface $fieldSettings)
    {
        $limit = $fieldSettings->getLimit();

        $fieldOptions = [
            'label' => false,
            'required' => $fieldSettings->isRequired(),
            'choices' => $fieldSettings->getAllowedValues(),
            'multiple' => $limit !== 1 ? true : false,
            'expanded' => $limit !== 1 ? true : false,
            'placeholder' => 'teebb.core.form.select_list_numeric'
        ];

        if ($limit == 1) {
            $fieldOptions['attr'] = [
                'class' => 'col-12 col-sm-6 form-control-sm'
            ];
        }

        if ($fieldSettings->isRequired()) {
            switch ($limit) {
                case 0:
                    $fieldOptions['constraints'] = [
                        new Count(['min' => 1, 'max' => sizeof($fieldSettings->getAllowedValues())])
                    ];
                    break;
                case 1:
                    $fieldOptions['constraints'] = [
                        new NotBlank()
                    ];
                    break;
                default:
                    $fieldOptions['constraints'] = [
                        new Count(['min' => 1, 'max' => $limit])
                    ];
                    break;
            }
        }

        return $fieldOptions;
    }

    /**
     * 配置string、stringFormat类型字段value表单options
     *
     * @param FieldDepartConfigurationInterface $fieldSettings
     * @return array
     */
    public function configStringFieldOptions(FieldDepartConfigurationInterface $fieldSettings)
    {
        if ($fieldSettings->isRequired()) {
            $fieldOptions['constraints'] = [
                new NotBlank(),
                new Length([
                    'min' => 1,
                    'max' => $fieldSettings->getLength()
                ])
            ];
        }
        $fieldOptions['label'] = false;
        $fieldOptions['attr'] = [
            'class' => 'form-control form-control-sm col-12 col-sm-6',
            'maxlength' => $fieldSettings->getLength()
        ];

        return $fieldOptions;
    }

    /**
     * 配置text、textFormat类型字段value表单options
     *
     * @param FieldDepartConfigurationInterface $fieldSettings
     * @return array
     */
    public function configTextFieldOptions(FieldDepartConfigurationInterface $fieldSettings)
    {
        if ($fieldSettings->isRequired()) {
            $fieldOptions['constraints'] = [
                new NotBlank(),
            ];
        }
        $fieldOptions['label'] = false;
        $fieldOptions['attr'] = [
            'class' => 'form-control form-control-sm',
        ];

        return $fieldOptions;
    }
}