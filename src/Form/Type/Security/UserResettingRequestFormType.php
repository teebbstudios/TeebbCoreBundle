<?php


namespace Teebb\CoreBundle\Form\Type\Security;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\User;

class UserResettingRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control-user',
                    'placeholder' => 'teebb.core.form.email'
                ]
            ])
            ->add('submitBtn', SubmitType::class,[
                'label' => 'teebb.core.form.reset_password',
                'attr' => [
                    'class' => 'btn-primary btn-user btn-block'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                'class' => 'user'
            ]
        ]);
    }
}