<?php


namespace Teebb\CoreBundle\Form\Type\FieldConfiguration;


use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\Configuration\ReferenceImageItemConfiguration;
use Teebb\CoreBundle\Form\Type\FieldFileAllowExtType;
use Teebb\CoreBundle\Form\Type\FieldImageResolutionType;

class ReferenceImageItemConfigurationType extends BaseItemConfigurationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('allowExt', FieldFileAllowExtType::class, [
                'label' => 'teebb.core.fields.configuration.allow_ext',
                'help' => 'teebb.core.fields.configuration.allow_ext_help',
                'data' => 'jpg,png,jpeg'
            ]);
        $this->buildFileFieldCommonForm($builder, $options);

        $builder
            ->add('maxResolution', FieldImageResolutionType::class, [
                'label' => 'teebb.core.fields.configuration.max_resolution',
                'help' => 'teebb.core.fields.configuration.max_resolution_help',
                'required' => false
            ])
            ->add('minResolution', FieldImageResolutionType::class, [
                'label' => 'teebb.core.fields.configuration.min_resolution',
                'help' => 'teebb.core.fields.configuration.min_resolution_help',
                'required' => false
            ])
            ->add('useAlt', CheckboxType::class, [
                'label' => 'teebb.core.fields.configuration.use_alt',
                'help' => 'teebb.core.fields.configuration.use_alt_help',
                'required' => false,
            ])
            ->add('altRequired', CheckboxType::class, [
                'label' => 'teebb.core.fields.configuration.alt_required',
                'help' => 'teebb.core.fields.configuration.alt_required_help',
                'required' => false,
            ])
            ->add('useTitle', CheckboxType::class, [
                'label' => 'teebb.core.fields.configuration.use_title',
                'help' => 'teebb.core.fields.configuration.use_title_help',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReferenceImageItemConfiguration::class
        ]);
    }
}