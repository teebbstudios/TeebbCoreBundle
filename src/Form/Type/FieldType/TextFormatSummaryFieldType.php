<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Form\Type\SummaryType;
use Teebb\CoreBundle\Form\Type\TextFormatterType;
use Teebb\CoreBundle\Form\Type\TextFormatTextareaType;

class TextFormatSummaryFieldType extends BaseFieldType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('summary', SummaryType::class, [
                'show_summary' => $options['show_summary'],
                'summary_required' => $options['summary_required'],
                'label_attr' => [
                    'class' => 'sr-only'
                ]
            ])
            ->add('value', TextFormatTextareaType::class)
//            ->add('formatter', TextFormatterType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $this->baseOptions($resolver);
        $resolver->setDefined('show_summary');
        $resolver->setDefined('summary_required');

        $resolver->setAllowedTypes('show_summary', 'boolean');
        $resolver->setAllowedTypes('summary_required', 'boolean');
    }
}