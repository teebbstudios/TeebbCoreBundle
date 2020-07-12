<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\Configuration\TextFormatItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\SimpleFormatItem;
use Teebb\CoreBundle\Form\Type\TextFormatTextareaType;

class TextFormatFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->transformSubmitNullDataToObject($builder, $options);

        /**@var TextFormatItemConfiguration $fieldSettings * */
        $fieldSettings = $options['field_configuration']->getSettings();

        $fieldOptions = $this->configTextFieldOptions($fieldSettings);

        $builder
            ->add('value', TextFormatTextareaType::class, $fieldOptions)
//            ->add('formatter', TextFormatterType::class, [
//                'label' => false,
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SimpleFormatItem::class,
        ]);

        $this->baseConfigOptions($resolver);
    }
}