<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class BooleanFieldType extends BaseFieldType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }

    public function getParent()
    {
        return CheckboxType::class;
    }

}