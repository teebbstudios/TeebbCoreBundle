<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\ReferenceImageItem;

class ReferenceImageFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReferenceImageItem::class,
        ]);

        $this->baseConfigOptions($resolver);
    }
}