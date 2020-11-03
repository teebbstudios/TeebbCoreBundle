<?php


namespace Teebb\CoreBundle\Form\Type\Options;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Options\System;

class SystemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('siteName', TextType::class, [
                'label' => 'teebb.core.form.site_name',
                'label_attr' => [
                    'class' => 'font-weight-bold'
                ],
                'attr' => [
                    'class' => 'form-control-sm'
                ]
            ])
            ->add('siteSlogan', TextType::class, [
                'label' => 'teebb.core.form.site_slogan',
                'label_attr' => [
                    'class' => 'font-weight-bold'
                ],
                'required' => false,
                'attr' => [
                    'class' => 'form-control-sm'
                ]
            ])
            ->add('siteEmail', EmailType::class, [
                'label' => 'teebb.core.form.site_email',
                'label_attr' => [
                    'class' => 'font-weight-bold'
                ],
                'attr' => [
                    'class' => 'form-control-sm'
                ]
            ])
            ->add('icpCode', TextType::class, [
                'label' => 'teebb.core.form.icp_code',
                'label_attr' => [
                    'class' => 'font-weight-bold'
                ],
                'required' => false,
                'attr' => [
                    'class' => 'form-control-sm'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => System::class,
        ]);
    }

}