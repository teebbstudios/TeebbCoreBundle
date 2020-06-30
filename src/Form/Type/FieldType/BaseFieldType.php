<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $builder->addEventListener(FormEvents::PRE_SET_DATA,
//            function (FormEvent $event) use ($options) {
//                $event->setData($options['data']);
//        });
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (isset($options['limit'])) {
            $view->vars['limit'] = $options['limit'];
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'mapped' => false,
            'allow_extra_fields' => true,
            'attr' => [
                'class' => 'prototype-wrapper'
            ]
        ]);
        $resolver->setDefined('limit');
        $resolver->setDefined('field_data');
        $resolver->setAllowedTypes('limit', 'integer');
        $resolver->setAllowedTypes('field_data', 'array');
    }

    public function getParent()
    {
        return CollectionType::class;
    }

    public function getBlockPrefix()
    {
        return 'base_field';
    }
}