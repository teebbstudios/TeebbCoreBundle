<?php


namespace Teebb\CoreBundle\Form\Type\Content;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Teebb\CoreBundle\Entity\Content;

/**
 * 内容Content表单
 */
class ContentType extends BaseContentType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //表单提交时设置Content type
        $builder->addEventListener(FormEvents::SUBMIT,
            function (FormEvent $event) use ($options) {
                /**@var Content $content * */
                $content = $event->getData();
                if (null == $content->getType()) {
                    $content->setType($options['type_alias']);
                    $event->setData($content);
                }
            });

        $data = $builder->getData();

        //添加标题字段，所有内容都有一个标题
        $builder->add('title', TextType::class, [
            'label' => 'teebb.core.form.title',
            'label_attr' => [
                'class' => 'font-weight-bold '
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
    }
}