<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseFieldType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $this->baseOptions($resolver);
    }

    protected function baseOptions(OptionsResolver $resolver){
        $resolver->setDefaults(['mapped' => false]);

        $resolver->setDefined('field_configuration');
        $resolver->setDefined('field_service');
        $resolver->setDefined('limit');
        $resolver->setDefined('field_object');

        $resolver->setAllowedTypes('field_configuration', 'object');
        $resolver->setAllowedTypes('field_service', 'object');
        $resolver->setAllowedTypes('limit', 'integer');
        $resolver->setAllowedTypes('field_object', 'object');
    }
}