<?php


namespace Teebb\CoreBundle\Form\Type\FieldConfiguration;


use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\Configuration\ReferenceFileItemConfiguration;
use Teebb\CoreBundle\Form\Type\FieldFileAllowExtType;

class ReferenceFileItemConfigurationType extends BaseItemConfigurationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('showControl', CheckboxType::class, [
                'label' => 'teebb.core.fields.configuration.show_control',
                'help' => 'teebb.core.fields.configuration.show_control_help',
                'data' => true
            ])
            ->add('allowExt', FieldFileAllowExtType::class, [
                'label' => 'teebb.core.fields.configuration.allow_ext',
                'help' => 'teebb.core.fields.configuration.allow_ext_help',
                'empty_data' => 'txt,doc'
            ]);

        $this->buildFileFieldCommonForm($builder, $options);

        $builder->add('useDescription', CheckboxType::class, [
            'label' => 'teebb.core.fields.configuration.use_description',
            'help' => 'teebb.core.fields.configuration.use_description_help',
            'data' => true
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReferenceFileItemConfiguration::class
        ]);
    }
}