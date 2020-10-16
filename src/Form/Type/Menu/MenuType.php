<?php


namespace Teebb\CoreBundle\Form\Type\Menu;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Menu;


class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'teebb.core.menu.name',
                'label_attr' => [
                    'class' => 'font-weight-bold'
                ],
                'attr' => [
                    'class' => 'form-control-sm col-md-6 transliterate'
                ],
                'help' => 'teebb.core.menu.name_help'
            ])
            ->add('menuAlias', TextType::class, [
                'label' => 'teebb.core.menu.menu_alias',
                'help' => 'teebb.core.menu.menu_alias_help',
                'attr' => [
                    'class' => 'form-control-sm col-md-6 input-alias'
                ],
                'label_attr' => [
                    'class' => 'font-weight-bold'
                ],
                'disabled' => $builder->getData() ? true : false
            ])
            ->add('description', TextareaType::class, [
                'label' => 'teebb.core.menu.menu_description',
                'label_attr' => [
                    'class' => 'font-weight-bold'
                ],
                'required' => false,
                'attr' => [
                    'class' => 'form-control-sm'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Menu::class
        ]);
    }

}