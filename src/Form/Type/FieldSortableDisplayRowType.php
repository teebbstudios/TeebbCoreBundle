<?php


namespace Teebb\CoreBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldSortableDisplayRowType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (isset($options['field_type'])) {
            $view->vars['field_type'] = $options['field_type'];
        }
        if (isset($options['type_alias'])) {
            $view->vars['type_alias'] = $options['type_alias'];
        }
        if (isset($options['field_alias'])) {
            $view->vars['field_alias'] = $options['field_alias'];
        }
    }

    public function getParent()
    {
        return IntegerType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined('field_type');
        $resolver->setDefined('type_alias');
        $resolver->setDefined('field_alias');
        $resolver->setAllowedTypes('field_type', 'string');
        $resolver->setAllowedTypes('type_alias', 'string');
        $resolver->setAllowedTypes('field_alias', 'string');
    }

}