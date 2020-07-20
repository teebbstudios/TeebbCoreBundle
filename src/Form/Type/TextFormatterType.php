<?php


namespace Teebb\CoreBundle\Form\Type;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\TextFormat\Formatter;

class TextFormatterType extends AbstractType
{
    /**
     * Todo: 需要根据用户组设置不同的过滤器
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'teebb.core.form.formatter_name',
            'class' => Formatter::class,
            'choice_label' => 'name',
            'attr' => [
                'class' => 'form-control-sm'
            ],
            'row_attr' => [
                'class' => 'form-inline'
            ]
        ]);
    }

    public function getParent()
    {
        return EntityType::class;
    }
}