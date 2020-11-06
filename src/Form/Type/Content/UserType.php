<?php


namespace Teebb\CoreBundle\Form\Type\Content;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
use Teebb\CoreBundle\Entity\Group;
use Teebb\CoreBundle\Entity\User;

class UserType extends BaseContentType
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        parent::__construct($entityManager, $container);
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /**@var User $user * */
            $user = $event->getData();
            if ($plainPassword = $user->getPlainPassword()) {
                $password = $this->userPasswordEncoder->encodePassword($user, $plainPassword);
                $user->setPassword($password);
            }
            //根据用户选择的group 动态修改用户roles
            $roles = [];
            foreach ($user->getGroups() as $group) {
                foreach ($group->getRoles() as $role) {
                    if ($role !== 'ROLE_USER') {
                        $roles[] = $role;
                    }
                }
            }
            $user->setRoles($roles);
            $event->setData($user);
        });

        $data = $builder->getData();

        $builder
            ->add('firstName', TextType::class, [
                'label' => 'teebb.core.form.first_name',
                'label_attr' => [
                    'class' => 'font-weight-bold'
                ],
                'attr' => [
                    'class' => 'form-control-sm'
                ],
                'required' => false,
            ])
            ->add('lastName', TextType::class, [
                'label' => 'teebb.core.form.last_name',
                'label_attr' => [
                    'class' => 'font-weight-bold'
                ],
                'attr' => [
                    'class' => 'form-control-sm'
                ],
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'label' => 'teebb.core.form.email',
                'label_attr' => [
                    'class' => 'font-weight-bold'
                ],
                'attr' => [
                    'class' => 'form-control-sm'
                ],
                'disabled' => $options['bool_profile'] ? true : false,
            ])
            ->add('username', TextType::class, [
                'label' => 'teebb.core.form.username',
                'label_attr' => [
                    'class' => 'font-weight-bold'
                ],
                'attr' => [
                    'class' => 'form-control-sm'
                ],
                'disabled' => $options['bool_profile'] ? true : false,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'label' => 'teebb.core.form.change_password',
                'label_attr' => [
                    'class' => 'font-weight-bold'
                ],
                'type' => PasswordType::class,
                'required' => false,
                'first_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'form-control-sm',
                        'placeholder' => 'teebb.core.form.password'
                    ],
                    'constraints' => [
                        new Length(['min' => 8])
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'form-control-sm',
                        'placeholder' => 'teebb.core.form.repeat_password'
                    ],
                    'constraints' => [
                        new Length(['min' => 8])
                    ]
                ],
                'help' => 'teebb.core.form.change_password_help'
            ]);

        //如果不是个人资料页
        if (!$options['bool_profile']) {
            $builder
                ->add('enabled', CheckboxType::class, [
                    'label' => 'teebb.core.form.enabled',
                    'label_attr' => [
                        'class' => 'font-weight-bold'
                    ],
                    'required' => false,
                ])
                ->add('accountNonLocked', CheckboxType::class, [
                    'label' => 'teebb.core.form.none_locked',
                    'label_attr' => [
                        'class' => 'font-weight-bold'
                    ],
                    'required' => false,
                ])
                ->add('groups', EntityType::class, [
                    'label' => 'teebb.core.form.group',
                    'class' => Group::class,
                    'choice_label' => 'name',
                    'multiple' => true,
                    'expanded' => true,
                ]);
        }


        $this->dynamicAddFieldForm($builder, $options, $data);

        $builder
            ->add('submit', SubmitType::class, [
                'label' => 'teebb.core.form.submit',
                'attr' => [
                    'class' => 'btn-primary btn-sm'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        //如果表单用于个人资料页,bool_profile控制表单行显示与禁用
        $resolver->setDefaults([
            'bool_profile' => false
        ]);
    }


}