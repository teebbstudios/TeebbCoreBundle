<?php


namespace Teebb\CoreBundle\Form\Type\Security;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

/**
 * 重置用户密码表单
 */
class UserResettingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => false,
                'first_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'form-control-user',
                        'placeholder' => 'teebb.core.form.password'
                    ],
                    'constraints' => [
                        new Length(['min' => 8])
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'form-control-user',
                        'placeholder' => 'teebb.core.form.repeat_password'
                    ],
                    'constraints' => [
                        new Length(['min' => 8])
                    ]
                ],
            ])
            ->add('resettingBtn', SubmitType::class, [
                'label' => 'teebb.core.form.reset_password',
                'attr' => [
                    'class' => 'btn-primary btn-user btn-block'
                ],
            ]);
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