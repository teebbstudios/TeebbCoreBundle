<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReferenceUserFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
//            'data_class' => ReferenceFileItem::class,
        ]);

        $this->baseConfigOptions($resolver);
    }
}