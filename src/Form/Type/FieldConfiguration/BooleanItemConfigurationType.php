<?php


namespace Teebb\CoreBundle\Form\Type\FieldConfiguration;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Teebb\CoreBundle\Entity\Fields\Configuration\BooleanItemConfiguration;

class BooleanItemConfigurationType extends BaseItemConfigurationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setFieldRequiredAndLimitOne($builder, $options, true);

        parent::buildForm($builder, $options);

        $builder
            ->add('onLabel', TextType::class, [
                'label'=>'teebb.core.fields.configuration.on_label',
                'attr' => [
                    'class' => 'col-12 col-sm-6 form-control-sm'
                ],
                'constraints' =>[
                    new NotBlank()
                ]
            ])
            ->add('offLabel', TextType::class, [
                'label'=>'teebb.core.fields.configuration.off_label',
                'attr' => [
                    'class' => 'col-12 col-sm-6 form-control-sm'
                ],
                'constraints' =>[
                    new NotBlank()
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>BooleanItemConfiguration::class
        ]);
    }
}