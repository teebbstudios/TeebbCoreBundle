<?php


namespace Teebb\CoreBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class FieldFileType extends AbstractType
{
    public function getParent()
    {
        return FileType::class;
    }

    public function getBlockPrefix()
    {
        return 'field_file';
    }
}