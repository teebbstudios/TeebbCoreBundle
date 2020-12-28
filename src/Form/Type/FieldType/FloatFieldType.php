<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\Configuration\FloatItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\FloatItem;

class FloatFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->transformSubmitNullDataToObject($builder, $options);

        /**@var FloatItemConfiguration $fieldSettings * */
        $fieldSettings = $options['field_configuration']->getSettings();

        $fieldOptions = [
            'label' => false,
            'attr' => [
                'class' => 'form-control-sm'
            ]
        ];

        $fieldOptions = $this->configNumericFieldOptions($fieldSettings, $fieldOptions);

        $builder->add('value', NumberType::class, $fieldOptions);

        //如果不限制字段数量则添加删除当前行按钮
        $this->addRemoveFieldButton($builder, $options['field_configuration'], $options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FloatItem::class,
            'attr' => [
                'class' => 'position-relative'
            ]
        ]);

        $this->baseConfigOptions($resolver);
    }
}