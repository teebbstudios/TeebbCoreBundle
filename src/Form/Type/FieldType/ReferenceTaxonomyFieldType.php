<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\ReferenceEntityItem;
use Teebb\CoreBundle\Entity\Fields\ReferenceTaxonomyItem;

class ReferenceTaxonomyFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReferenceTaxonomyItem::class,
        ]);

        $this->baseConfigOptions($resolver);
    }
}