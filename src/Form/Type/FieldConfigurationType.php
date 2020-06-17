<?php


namespace Teebb\CoreBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;

/**
 * 编辑字段表单
 *
 */
class FieldConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bundle', HiddenType::class)
            ->add('typeAlias', HiddenType::class)
            ->add('fieldLabel', TextType::class)
            ->add('fieldAlias', AliasValueType::class)
            ->add('fieldType', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FieldConfiguration::class
        ]);
    }
}