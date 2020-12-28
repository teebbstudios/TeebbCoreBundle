<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\Configuration\StringItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\StringItem;

class StringFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->transformSubmitNullDataToObject($builder, $options);

        /**@var StringItemConfiguration $fieldSettings * */
        $fieldSettings = $options['field_configuration']->getSettings();

        $fieldOptions = $this->configStringFieldOptions($fieldSettings);

        $builder->add('value', TextType::class, $fieldOptions);

        //如果不限制字段数量则添加删除当前行按钮
        $this->addRemoveFieldButton($builder, $options['field_configuration'], $options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StringItem::class,
            'attr' => [
                'class' => 'position-relative'
            ]
        ]);

        $this->baseConfigOptions($resolver);
    }
}