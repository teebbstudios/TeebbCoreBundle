<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\Configuration\StringItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\SimpleValueItem;

class StringFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**@var StringItemConfiguration $fieldSettings * */
        $fieldSettings = $options['field_configuration']->getSettings();

        $fieldOptions = $this->configStringFieldOptions($fieldSettings);

        $builder->add('value', TextType::class, $fieldOptions);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SimpleValueItem::class,
        ]);

        $this->baseConfigOptions($resolver);
    }
}