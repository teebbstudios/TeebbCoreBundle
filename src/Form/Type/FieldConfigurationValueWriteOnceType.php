<?php


namespace Teebb\CoreBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * 字段设置中某些设置将影响数据库的存储，比如文本长度、Decimal精度和小数点后长度，此种设置应为仅新建时可写入，一但有值将不可更改
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class FieldConfigurationValueWriteOnceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //在预加载表单之前，如果此表单行有数据则设置为disabled不可更改
        $builder->addEventListener(FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($builder, $options) {
                if (null !== $data = $event->getData()) {
                    $builder->setDisabled(true);
                    $builder->setDataLocked(true);
                }
            }
        );
    }

    public function getParent()
    {
        return IntegerType::class;
    }

}