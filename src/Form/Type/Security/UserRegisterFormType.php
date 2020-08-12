<?php


namespace Teebb\CoreBundle\Form\Type\Security;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Teebb\CoreBundle\Entity\User;
use Teebb\CoreBundle\Utils\Canonicalizer;

class UserRegisterFormType extends AbstractType
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;
    /**
     * @var Canonicalizer
     */
    private $canonicalizer;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->canonicalizer = new Canonicalizer();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /**@var User $user**/
            $user = $event->getData();
            if (null !== $user->getPlainPassword()){
                $user->setEmailCanonical($this->canonicalizer->canonicalize($user->getEmail()));
                $user->setUsernameCanonical($this->canonicalizer->canonicalize($user->getUsername()));
                $user->setPassword($this->userPasswordEncoder->encodePassword($user, $user->getPlainPassword()));
                $user->setCreatedAt(new \DateTime());
                $user->setUpdatedAt(new \DateTime());

                $event->setData($user);
            }
        });

        $builder
            ->add('username', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control-user',
                    'placeholder' => 'teebb.core.form.username'
                ]
            ])
            ->add('firstName', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control-user',
                    'placeholder' => 'teebb.core.form.first_name'
                ],
                'row_attr' => [
                    'class' => 'col-sm-6 mb-3 mb-sm-0'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control-user',
                    'placeholder' => 'teebb.core.form.last_name'
                ],
                'row_attr' => [
                    'class' => 'col-sm-6 mb-3 mb-sm-0'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control-user',
                    'placeholder' => 'teebb.core.form.email'
                ]
            ])
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
            ->add('registerBtn', SubmitType::class, [
                'label' => 'teebb.core.form.register',
                'attr' => [
                    'class' => 'btn-primary btn-user btn-block'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => [
                'class' => 'user'
            ]
        ]);
    }

}