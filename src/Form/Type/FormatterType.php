<?php


namespace Teebb\CoreBundle\Form\Type;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\TextFormat\Formatter;

/**
 * 文本过滤器表单
 */
class FormatterType extends AbstractType
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'teebb.core.form.formatter_name',
            'attr' => [
                'class' => 'form-control-sm transliterate'
            ],
            'label_attr' => [
                'class' => 'font-weight-bold'
            ]
        ]);

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
        // Todo: 添加用户组           ->add('roles', )
        $builder
            ->add('filterSettings', FilterSettingsType::class, [
                'label' => 'teebb.core.form.filter_settings',
                'filter_settings' => $this->container->getParameter('filter_settings'),
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