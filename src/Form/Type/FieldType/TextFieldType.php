<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\Configuration\TextItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\SimpleValueItem;

class TextFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**@var TextItemConfiguration $fieldSettings * */
        $fieldSettings = $options['field_configuration']->getSettings();

        $fieldOptions = $this->configTextFieldOptions($fieldSettings);

        $builder->add('value', TextareaType::class, $fieldOptions);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SimpleValueItem::class,
        ]);

        $this->baseConfigOptions($resolver);
    }
}