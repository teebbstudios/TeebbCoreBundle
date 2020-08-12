<?php


namespace Teebb\CoreBundle\Form\Type\Security;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\User;

class UserLoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control-user',
                    'placeholder' => 'teebb.core.form.username'
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control-user',
                    'placeholder' => 'teebb.core.form.password'
                ]
            ])
            ->add('_remember_me', CheckboxType::class, [
                'label' => 'teebb.core.form.remember_me',
                'required' => false,
                'mapped' => false,
            ])
            ->add('loginBtn', SubmitType::class, [
                'label' => 'teebb.core.form.login',
                'attr' => [
                    'class' => 'btn-primary btn-user btn-block'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => [
                'class' => 'user'
            ],
            'csrf_token_id' => 'login_token'
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

}