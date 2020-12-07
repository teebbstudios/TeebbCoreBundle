<?php


namespace Teebb\CoreBundle\Form\Type\User;


use FOS\CKEditorBundle\Config\CKEditorConfigurationInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Group;


class GroupType extends AbstractType
{
    private $ckeditorConfigs;

    public function __construct(CKEditorConfigurationInterface $editorConfiguration)
    {
        $this->ckeditorConfigs = $editorConfiguration->getConfigs();
    }

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

        //ckeditor配置名称数组
        $ckeditorConfigChoices = [];
        foreach ($this->ckeditorConfigs as $configKey=>$config){
            $ckeditorConfigChoices[$configKey] = $configKey;
        }

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
            ->add('ckeditorConfig', ChoiceType::class, [
                'label' => 'teebb.core.user.ckeditor_config',
                'help' => 'teebb.core.user.ckeditor_config_help',
                'attr' => [
                    'class' => 'form-control-sm col-md-6 input-alias'
                ],
                'label_attr' => [
                    'class' => 'font-weight-bold'
                ],
                'choices' => $ckeditorConfigChoices
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