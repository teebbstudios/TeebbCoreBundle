<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\BaseFieldItem;
use Teebb\CoreBundle\Entity\Fields\Configuration\StringFormatItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\StringFormatItem;
use Teebb\CoreBundle\Entity\TextFormat\Formatter;
use Teebb\CoreBundle\Form\Type\TextFormatterType;
use Teebb\CoreBundle\TextFilter\TextFilterInterface;

/**
 * 文本已格式化字段表单类型
 */
class StringFormatFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $formatterRepo;

    /**
     * @var array
     */
    private $filterSettings;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->formatterRepo = $this->container->get('doctrine.orm.default_entity_manager')->getRepository(Formatter::class);
        $this->filterSettings = $this->container->getParameter('teebb.core.formatter.filter_settings');
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
         * 格式化器过滤提交的文本
         */
        $this->filterFormatFieldValue($builder, $this->formatterRepo, $this->filterSettings);

        $this->transformSubmitNullDataToObject($builder, $options);

        /**@var StringFormatItemConfiguration $fieldSettings * */
        $fieldSettings = $options['field_configuration']->getSettings();

        $fieldOptions = $this->configStringFieldOptions($fieldSettings);

        $builder
            ->add('value', TextType::class, $fieldOptions)
            ->add('formatter', TextFormatterType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StringFormatItem::class,
        ]);

        $this->baseConfigOptions($resolver);
    }

}