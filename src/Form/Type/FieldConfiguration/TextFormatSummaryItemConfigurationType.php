<?php


namespace Teebb\CoreBundle\Form\Type\FieldConfiguration;


use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\Configuration\TextFormatSummaryItemConfiguration;

class TextFormatSummaryItemConfigurationType extends BaseItemConfigurationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('showSummaryInput', CheckboxType::class, [
                'label' => 'teebb.core.fields.configuration.show_summary_input_label',
                'help' => 'teebb.core.fields.configuration.show_summary_input_label_help',
                'required' => false,
                'data' => true
            ])
            ->add('summaryRequired', CheckboxType::class, [
                'label' => 'teebb.core.fields.configuration.summary_require_label',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TextFormatSummaryItemConfiguration::class
        ]);
    }
}