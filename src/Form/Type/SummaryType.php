<?php


namespace Teebb\CoreBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SummaryType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['show_summary'] = $options['show_summary'];
        $view->vars['summary_required'] = $options['summary_required'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                'class' => 'form-control form-control-sm'
            ]
        ]);

        $resolver->setDefined('show_summary');
        $resolver->setDefined('summary_required');
        $resolver->setAllowedTypes('show_summary', 'boolean');
        $resolver->setAllowedTypes('summary_required', 'boolean');
    }

    public function getParent()
    {
        return TextareaType::class;
    }

    public function getBlockPrefix()
    {
        return 'text_summary';
    }
}