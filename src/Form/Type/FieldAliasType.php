<?php


namespace Teebb\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * 字段别名Type
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class FieldAliasType extends AbstractType
{
    public function getBlockPrefix()
    {
        return 'teebb_field_alias';
    }

    public function getParent()
    {
        return TextType::class;
    }

}