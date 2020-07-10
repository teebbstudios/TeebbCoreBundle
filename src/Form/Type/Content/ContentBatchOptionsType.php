<?php


namespace Teebb\CoreBundle\Form\Type\Content;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Content内容批量操作Type
 */
class ContentBatchOptionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('batch', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'batch_delete' => 'batch_delete',
                    'batch_unpublish' => 'batch_unpublish',
                    'batch_publish' => 'batch_publish'
                ],
                'attr' => [
                    'class' => 'form-control-sm'
                ],
                'row_attr' => [
                    'class' => 'mr-2'
                ],
                'placeholder' => 'teebb.core.form.batch_action'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'teebb.core.form.submit',
                'attr' => [
                    'class' => 'btn-sm btn-secondary'
                ]
            ])
            ->add('limit', ChoiceType::class, [
                'label' => 'teebb.core.form.page_limit',
                'choices' => ['10' => 10, '25' => 25, '50' => 50, '100' => 100],
                'attr' => [
                    'class' => 'form-control-sm page-limit-select'
                ],
                'row_attr' => [
                    'class' => 'ml-auto'
                ],
                'placeholder' => false,
                'mapped' => false,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                'class' => 'form-inline w-100'
            ]
        ]);
    }
}