<?php


namespace Teebb\CoreBundle\Form\Type\FieldConfiguration;


use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Teebb\CoreBundle\Entity\Fields\Configuration\DecimalItemConfiguration;

class DecimalItemConfigurationType extends BaseItemConfigurationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        //添加表单其他行
        $this->buildNumericFieldsForm($builder, $options);

        //在预加载表单之前，如果此表单行有数据则设置为disabled不可更改
        $builder->addEventListener(FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($builder, $options) {
                /**@var DecimalItemConfiguration $data * */
                $data = $event->getData();

                $form = $event->getForm();

                $form
                    ->add('precision', IntegerType::class, [
                        'label' => 'teebb.core.fields.configuration.precision',
                        'attr' => [
                            'class' => 'col-12 col-sm-6 form-control-sm',
                            'min' => 1
                        ],
                        'constraints' => [
                            new GreaterThan(['value' => 1])
                        ],
                        'data' => $data ? $data->getPrecision() : 5,
                        'disabled' => $data ? true : false,
                        'help' => 'teebb.core.fields.configuration.precision_help'
                    ])
                    ->add('scale', IntegerType::class, [
                        'label' => 'teebb.core.fields.configuration.scale',
                        'attr' => [
                            'class' => 'col-12 col-sm-6 form-control-sm',
                            'min' => 0
                        ],
                        'constraints' => [
                            new PositiveOrZero()
                        ],
                        'data' => $data ? $data->getScale() : 2,
                        'disabled' => $data ? true : false,
                        'help' => 'teebb.core.fields.configuration.scale_help'
                    ]);
            }
        );


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DecimalItemConfiguration::class
        ]);
    }

}