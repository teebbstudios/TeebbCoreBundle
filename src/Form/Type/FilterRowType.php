<?php


namespace Teebb\CoreBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterRowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('filter_name', CheckboxType::class, [
            'label' => $options['filter_label'],
            'required' => false,
        ]);
        if (isset($options['extra_form_type'])) {
            $builder->add('filter_extra', $options['extra_form_type'], [
                'label' => $options['extra_label'],
                'help' => $options['extra_help'],
                'required' => false,
                'attr' => [
                    'class' => 'form-control-sm'
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined('filter_label');
        $resolver->setDefined('filter_class');
        $resolver->setDefined('extra_form_type');
        $resolver->setDefined('extra_label');
        $resolver->setDefined('extra_help');

        $resolver->setRequired('filter_label');
    }
}