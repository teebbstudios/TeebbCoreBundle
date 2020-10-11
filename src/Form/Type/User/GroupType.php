<?php


namespace Teebb\CoreBundle\Form\Type\User;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Group;


class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //提交数据时将组别名添加到roles中
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /**@var Group $group * */
            $group = $event->getData();
            $groupAlias = $group->getGroupAlias();
            $group->addRole('ROLE_' . ucwords($groupAlias));

            $event->setData($group);
        });

        $builder
            ->add('name', TextType::class, [
                'label' => 'teebb.core.user.group_name',
                'label_attr' => [
                    'class' => 'font-weight-bold'
                ],
                'attr' => [
                    'class' => 'form-control-sm col-md-6 transliterate'
                ],
                'help' => 'teebb.core.user.group_name_help'
            ])
            ->add('groupAlias', TextType::class, [
                'label' => 'teebb.core.user.group_alias',
                'help' => 'teebb.core.user.group_alias_help',
                'attr' => [
                    'class' => 'form-control-sm col-md-6 input-alias'
                ],
                'label_attr' => [
                    'class' => 'font-weight-bold'
                ],
                'disabled' => $builder->getData() ? true : false
            ])
            ->add('permissions', PermissionsType::class, [
                'label' => 'teebb.core.user.group_permissions',
                'label_attr' => [
                    'class' => 'font-weight-bold'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'teebb.core.form.submit',
                'attr' => [
                    'class' => 'btn-primary btn-sm'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Group::class
        ]);
    }
}