<?php


namespace Teebb\CoreBundle\Form\Type;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Teebb\CoreBundle\Entity\Types\Types;

/**
 * 字段配置FieldConfiguration表单中引用内容、引用分类字段 表单行Type，
 * 在表单中动态显示内容的类型和分类的类型
 */
class FieldConfigurationReferenceEntityType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Types::class,
            'expanded' => true,
            'multiple' => true,
            'choice_label' => 'label',
            'choice_value' => 'typeAlias',
            'constraints' => [
                new Count(['min' => 1])
            ]
        ]);
    }

    public function getParent()
    {
        return EntityType::class;
    }

}