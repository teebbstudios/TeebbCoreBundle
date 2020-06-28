<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class StringFieldType extends BaseFieldType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }

    public function getParent()
    {
        return TextType::class;
    }
}