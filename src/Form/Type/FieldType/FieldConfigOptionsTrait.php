<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\OptionsResolver\OptionsResolver;

trait FieldConfigOptionsTrait
{
    public function baseConfigOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => false,
            'row_attr' => [
                'class' => 'form-item-wrapper mb-0'
            ],
        ]);

        $resolver->setDefined('field_configuration');
        $resolver->setDefined('field_service');

        $resolver->setAllowedTypes('field_configuration', 'object');
        $resolver->setAllowedTypes('field_service', 'object');
    }
}