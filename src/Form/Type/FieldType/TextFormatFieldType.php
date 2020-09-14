<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\Configuration\TextFormatItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\TextFormatItem;
use Teebb\CoreBundle\Entity\Formatter;
use Teebb\CoreBundle\Form\Type\TextFormatterType;
use Teebb\CoreBundle\Form\Type\TextFormatTextareaType;

class TextFormatFieldType extends AbstractType
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

        /**@var TextFormatItemConfiguration $fieldSettings * */
        $fieldSettings = $options['field_configuration']->getSettings();

        $fieldOptions = $this->configTextFieldOptions($fieldSettings);

        $builder
            ->add('value', TextFormatTextareaType::class, $fieldOptions)
            ->add('formatter', TextFormatterType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TextFormatItem::class,
        ]);

        $this->baseConfigOptions($resolver);
    }
}