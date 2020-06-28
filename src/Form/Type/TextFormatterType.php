<?php


namespace Teebb\CoreBundle\Form\Type;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextFormatterType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        //todo: 配置不同的格式化器
    }

    public function getParent()
    {
        return EntityType::class;
    }
}