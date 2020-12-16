<?php


namespace Teebb\CoreBundle\Form\Type\Content;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Teebb\CoreBundle\Entity\Content;

/**
 * 内容Content表单
 */
class ContentType extends BaseContentType
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
        //表单提交时设置Content type
        $builder->addEventListener(FormEvents::SUBMIT,
            function (FormEvent $event) use ($options) {
                /**@var Content $content * */
                $content = $event->getData();
                if (null == $content->getTypeAlias()) {
                    $content->setTypeAlias($options['type_alias']);
                }
                if (null == $content->getAuthor()) {
                    $content->setAuthor($this->security->getUser());
                }
                $event->setData($content);
            });

        $data = $builder->getData();

        //添加标题字段，所有内容都有一个标题
        $builder->add('title', TextType::class, [
            'label' => 'teebb.core.form.title',
            'label_attr' => [
                'class' => 'font-weight-bold'
            ],
            'constraints' => [
                new NotBlank(),
                new Length(['min' => 1, 'max' => 255])
            ],
            'attr' => [
                'class' => 'form-control-sm'
            ]
        ]);

        $this->dynamicAddFieldForm($builder, $options, $data);

        //内容发布状态 草稿 已发布
        $builder
            ->add('slug', TextType::class, [
                'label' => 'teebb.core.form.slug',
                'label_attr' => ['class' => 'font-weight-bold'],
                'help' => 'teebb.core.form.slug_help',
                'required' => false,
                'attr' => [
                    'class' => 'form-control-sm'
                ],
                'constraints' => [
                    new Regex('/^[a-zA-Z0-9-]+$/')
                ]
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'teebb.core.form.publish_status',
                'label_attr' => ['class' => 'font-weight-bold'],
                'choices' => ['publish' => 'publish', 'draft' => 'draft'],
                'attr' => [
                    'class' => 'form-control-sm'
                ],
                'data' => $data ? $data->getStatus() : 'publish'
            ])
            ->add('boolTop', CheckboxType::class, [
                'label' => 'teebb.core.form.bool_top',
                'label_attr' => ['class' => 'font-weight-bold'],
                'attr' => [
                    'class' => 'pl-0'
                ],
                'required' => false
            ])
        ;
    }
}