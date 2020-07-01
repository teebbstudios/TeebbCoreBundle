<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\Configuration\StringFormatItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\SimpleFormatItem;

class StringFormatFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**@var StringFormatItemConfiguration $fieldSettings * */
        $fieldSettings = $options['field_configuration']->getSettings();

        $fieldOptions = $this->configStringFieldOptions($fieldSettings);

        $builder
            ->add('value', TextType::class, $fieldOptions)
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