<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Teebb\CoreBundle\Entity\Fields\Configuration\DatetimeItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\Configuration\TimestampItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\SimpleValueItem;

class TimestampFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->transformSubmitNullDataToObject($builder, $options);

        /**@var TimestampItemConfiguration $fieldSettings * */
        $fieldSettings = $options['field_configuration']->getSettings();

        $fieldOptions = [
            'label' => false,
            'widget' => 'single_text',
            'input' => 'timestamp',
            'attr' => [
                'class' => 'form-control-sm'
            ]
        ];

        if ($fieldSettings->isRequired()) {
            $fieldOptions['constraints'] = [
                new NotBlank(),
            ];
        }

        $builder->add('value', DateTimeType::class, $fieldOptions);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SimpleValueItem::class,
        ]);

        $this->baseConfigOptions($resolver);
    }
}