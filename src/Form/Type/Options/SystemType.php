<?php


namespace Teebb\CoreBundle\Form\Type\Options;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
            ])
            ->add('siteSlogan', TextType::class, [
                'label' => 'teebb.core.form.site_slogan',
                'required' => false,
            ])
            ->add('siteEmail', EmailType::class, [
                'label' => 'teebb.core.form.site_email',
            ])
            ->add('logo', FileType::class, [
                'label' => 'teebb.core.form.logo',
                'required' => false,
                'mapped' => false,
            ])
            ->add('icpCode', TextType::class, [
                'label' => 'teebb.core.form.icp_code',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => System::class
        ]);
    }

}