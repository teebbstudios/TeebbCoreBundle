<?php


namespace Teebb\CoreBundle\Form\Type\Content;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Teebb\CoreBundle\Entity\Comment;

/**
 * Comment表单
 */
class CommentType extends BaseContentType
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container, Security $security)
    {
        parent::__construct($entityManager, $container);
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //表单提交时设置Comment type
        $builder->addEventListener(FormEvents::SUBMIT,
            function (FormEvent $event) use ($options) {
                /**@var Comment $comment * */
                $comment = $event->getData();
                if (null == $comment->getCommentType()) {
                    $comment->setCommentType($options['type_alias']);
                }
                if (null == $comment->getAuthor()) {
                    $comment->setAuthor($this->security->getUser());
                }
                $event->setData($comment);
            });

        $data = $builder->getData();

        //Todo：此处判断用户是否已登录，如果没有登录则要输入姓名，邮件，主页
//        $builder
//            ->add('name', TextType::class, [
//                'label' => 'teebb.core.form.name',
//                'label_attr' => [
//                    'class' => 'font-weight-bold'
//                ],
//                'constraints' => [
//                    new NotBlank(),
//                    new Length(['min' => 1, 'max' => 255])
//                ],
//                'attr' => [
//                    'class' => 'form-control-sm'
//                ]
//            ])
//            ->add('email', EmailType::class, [
//                'label' => 'teebb.core.form.email',
//                'label_attr' => [
//                    'class' => 'font-weight-bold'
//                ],
//                'constraints' => [
//                    new NotBlank(),
//                    new Length(['min' => 1, 'max' => 255])
//                ],
//                'attr' => [
//                    'class' => 'form-control-sm'
//                ]
//            ])
//            ->add('homePage', TextType::class, [
//                'label' => 'teebb.core.form.email',
//                'label_attr' => [
//                    'class' => 'font-weight-bold'
//                ],
//                'constraints' => [
//                    new Email(),
//                    new Length(['min' => 1, 'max' => 255])
//                ],
//                'attr' => [
//                    'class' => 'form-control-sm'
//                ],
//                'required' => false,
//            ]);

        //添加主题字段
        $builder
            ->add('subject', TextType::class, [
                'label' => 'teebb.core.form.comment_subject',
                'label_attr' => [
                    'class' => 'font-weight-bold mr-3'
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 1, 'max' => 255])
                ],
                'attr' => [
                    'class' => 'form-control-sm w-50'
                ],
                'row_attr' => [
                    'class' => 'form-inline'
                ]
            ]);

        $this->dynamicAddFieldForm($builder, $options, $data);

        //添加按钮
        $builder
            ->add('submit', SubmitType::class, [
                'label' => 'teebb.core.form.submit',
                'attr' => [
                    'class' => 'btn btn-primary btn-sm'
                ]
            ]);
    }
}