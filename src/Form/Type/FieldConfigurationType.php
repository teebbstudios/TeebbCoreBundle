<?php


namespace Teebb\CoreBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Form\Type\FieldConfiguration\BooleanItemConfigurationType;
use Teebb\CoreBundle\Form\Type\FieldConfiguration\DatetimeItemConfigurationType;
use Teebb\CoreBundle\Form\Type\FieldConfiguration\DecimalItemConfigurationType;
use Teebb\CoreBundle\Form\Type\FieldConfiguration\EmailItemConfigurationType;
use Teebb\CoreBundle\Form\Type\FieldConfiguration\FloatItemConfigurationType;
use Teebb\CoreBundle\Form\Type\FieldConfiguration\IntegerItemConfigurationType;
use Teebb\CoreBundle\Form\Type\FieldConfiguration\ListFloatItemConfigurationType;
use Teebb\CoreBundle\Form\Type\FieldConfiguration\ListIntegerItemConfigurationType;

/**
 * 编辑字段表单
 */
class FieldConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bundle', HiddenType::class)
            ->add('typeAlias', HiddenType::class)
            ->add('fieldLabel', TextType::class, [
                'attr' => [
                    'class' => 'col-12 col-sm-6 form-control-sm'
                ],
                'help' => 'teebb.core.fields.configuration.label_help'
            ])
            ->add('fieldAlias', AliasValueType::class, [
                'help' => 'teebb.core.form.alias_help'
            ])
            ->add('fieldType', HiddenType::class)

            ->add('settings', ListIntegerItemConfigurationType::class, [
                'label' => 'teebb.core.fields.configuration.settings'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FieldConfiguration::class
        ]);
    }
}