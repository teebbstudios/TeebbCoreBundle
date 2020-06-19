<?php


namespace Teebb\CoreBundle\Form\Type\FieldConfiguration;


use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Teebb\CoreBundle\Entity\Fields\Configuration\DecimalItemConfiguration;
use Teebb\CoreBundle\Form\Type\FieldConfigurationValueWriteOnceType;

class DecimalItemConfigurationType extends BaseItemConfigurationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('precision', FieldConfigurationValueWriteOnceType::class, [
                'label' => 'teebb.core.fields.configuration.precision',
                'attr' => [
                    'class' => 'col-12 col-sm-6 form-control-sm',
                    'min' => 1
                ],
                'data' => 2,
                'constraints' => [
                    new GreaterThan(['value' => 1])
                ],
                'help' => 'teebb.core.fields.configuration.precision_help'
            ])
            ->add('scale', FieldConfigurationValueWriteOnceType::class, [
                'label' => 'teebb.core.fields.configuration.scale',
                'attr' => [
                    'class' => 'col-12 col-sm-6 form-control-sm',
                    'min' => 0
                ],
                'data' => 0,
                'constraints' => [
                    new PositiveOrZero()
                ],
                'help' => 'teebb.core.fields.configuration.scale_help'
            ]);

        $this->buildNumericFieldsForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DecimalItemConfiguration::class
        ]);
    }

}