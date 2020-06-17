<?php


namespace Teebb\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * 在编辑表单中别名alias显示为不可更改的文本值
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class AliasValueType extends AbstractType
{
    public function getBlockPrefix()
    {
        return 'teebb_alias_value';
    }

    public function getParent()
    {
        return TextType::class;
    }
}