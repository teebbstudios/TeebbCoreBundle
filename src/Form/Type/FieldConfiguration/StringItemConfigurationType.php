<?php


namespace Teebb\CoreBundle\Form\Type\FieldConfiguration;


use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\Configuration\StringItemConfiguration;

class StringItemConfigurationType extends BaseItemConfigurationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $this->buildStringFieldsForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StringItemConfiguration::class
        ]);
    }
}