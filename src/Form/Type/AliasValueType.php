<?php


namespace Teebb\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * 在编辑表单中别名alias显示为不可更改的文本值
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class AliasValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //在表单显示时获取fieldAlias
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder) {
            $builder->setData($event->getData());
        });
        //在表单提交前设置fieldAlias
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($builder) {
            $event->setData( $builder->getData());
        });
    }

    public function getBlockPrefix()
    {
        return 'teebb_alias_value';
    }

    public function getParent()
    {
        return TextType::class;
    }
}