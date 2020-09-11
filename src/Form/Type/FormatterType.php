<?php


namespace Teebb\CoreBundle\Form\Type;

use FOS\CKEditorBundle\Config\CKEditorConfigurationInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Group;
use Teebb\CoreBundle\Entity\TextFormat\Formatter;
use function Sodium\add;

/**
 * 文本过滤器表单
 */
class FormatterType extends AbstractType
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var CKEditorConfigurationInterface
     */
    private $CKEditorConfiguration;

    public function __construct(ContainerInterface $container, CKEditorConfigurationInterface $CKEditorConfiguration)
    {
        $this->container = $container;
        $this->CKEditorConfiguration = $CKEditorConfiguration;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $configs = $this->CKEditorConfiguration->getConfigs();
        $ckeditorConfigs = [];
        foreach ($configs as $configName => $config) {
            $ckeditorConfigs[$configName] = $configName;
        }
        $builder->add('name', TextType::class, [
            'label' => 'teebb.core.form.formatter_name',
            'attr' => [
                'class' => 'form-control-sm transliterate'
            ],
            'label_attr' => [
                'class' => 'font-weight-bold'
            ]
        ]);
        //编辑时不允许修改格式化器别名
        if ($builder->getData() == null) {
            $builder->add('alias', TextType::class, [
                'label' => 'teebb.core.form.alias',
                'help' => 'teebb.core.form.alias_help',
                'attr' => [
                    'class' => 'form-control-sm  input-alias'
                ],
                'label_attr' => [
                    'class' => 'font-weight-bold'
                ]
            ]);
        }
        $builder
            ->add('groups', EntityType::class, [
                'class' => Group::class,
                'label' => 'teebb.core.form.group',
                'label_attr' => [
                    'class' => 'font-weight-bold'
                ],
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('ckEditorConfig', ChoiceType::class, [
                'label' => 'teebb.core.form.ckeditor_config',
                'choices' => $ckeditorConfigs,
                'attr' => [
                    'class' => 'form-control-sm'
                ],
                'label_attr' => [
                    'class' => 'font-weight-bold'
                ],
                'help' => 'teebb.core.form.ckeditor_config_help',
            ])
            ->add('filterSettings', FilterSettingsType::class, [
                'label' => 'teebb.core.form.filter_settings',
                'filter_settings' => $this->container->getParameter('teebb.core.formatter.filter_settings'),
                'label_attr' => [
                    'class' => 'font-weight-bold'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formatter::class
        ]);
    }
}