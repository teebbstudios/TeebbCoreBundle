<?php


namespace Teebb\CoreBundle\Form\Type\Content;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Teebb\CoreBundle\Entity\Group;

class UserType extends BaseContentType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
            ])
            ->add('username', TextType::class, [
                'label' => 'teebb.core.form.username',
                'label_attr' => [
                    'class' => 'font-weight-bold'
                ],
                'attr' => [
                    'class' => 'form-control-sm'
                ],
            ])
            ->add('groups', EntityType::class, [
                'label' => 'teebb.core.form.group',
                'class' => Group::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ]);

        $this->dynamicAddFieldForm($builder, $options, $data);

        $builder
            ->add('submit', SubmitType::class, [
                'label' => 'teebb.core.form.submit',
                'attr' => [
                    'class' => 'btn-primary btn-sm'
                ]
            ]);
    }


}