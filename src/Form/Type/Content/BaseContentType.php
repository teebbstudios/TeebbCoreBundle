<?php


namespace Teebb\CoreBundle\Form\Type\Content;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Teebb\CoreBundle\AbstractService\FieldInterface;
use Teebb\CoreBundle\Entity\BaseContent;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Form\Type\FieldType\BaseFieldType;
use Teebb\CoreBundle\Repository\Fields\FieldConfigurationRepository;

class BaseContentType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FieldConfigurationRepository
     */
    private $fieldConfigurationsRepository;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        $this->entityManager = $entityManager;
        $this->fieldConfigurationsRepository = $entityManager->getRepository(FieldConfiguration::class);

        $this->container = $container;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true
        ]);

        $resolver->setDefined('bundle');
        $resolver->setDefined('type_alias');

        $resolver->setRequired('bundle');
        $resolver->setRequired('type_alias');

        $resolver->setAllowedTypes('bundle', 'string');
        $resolver->setAllowedTypes('type_alias', 'string');
    }

    /**
     * 动态添加字段的表单
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     * @param BaseContent|null $data
     */
    protected function dynamicAddFieldForm(FormBuilderInterface $builder, array $options, $data = null)
    {
        //获取当前内容类型所有字段
        $fields = $this->fieldConfigurationsRepository
            ->getBySortableGroupsQueryDesc(['bundle' => $options['bundle'], 'typeAlias' => $options['type_alias']])->getResult();

        /**@var FieldConfiguration $fieldConfiguration * */
        foreach ($fields as $fieldConfiguration) {
            $fieldType = $fieldConfiguration->getFieldType();
            /**@var FieldInterface $fieldService * */
            $fieldService = $this->container->get('teebb.core.field.' . $fieldType);

            $fieldSettings = $fieldConfiguration->getSettings();
            $limit = $fieldSettings->getLimit();

            $baseFieldOptions = [
                'label' => $fieldConfiguration->getFieldLabel(),
                'label_attr' => ['class' => 'font-weight-bold'],
                'help' => $fieldSettings->getDescription(),
                'required' => $fieldSettings->isRequired(),
                'limit' => $limit,
                'field_type' => $fieldType,
                'allow_add' => !in_array($fieldType, ['boolean', 'listInteger', 'listFloat']),
                'allow_delete' => !in_array($fieldType, ['boolean', 'listInteger', 'listFloat']),
//                'delete_empty' => function ($entity) use ($fieldSettings) {
//                    return false == $fieldSettings->isRequired() && (null == $entity || $entity->getValue() == null);
//                },
                'entry_type' => $fieldService->getFieldFormType(),
                'entry_options' => [
                    'label' => false,
                    'field_configuration' => $fieldConfiguration,
                    'field_service' => $fieldService,
                ],
            ];

            if ($fieldSettings->isRequired()) {
                $baseFieldOptions['constraints'] = [
                    new Count(['min' => 1])
                ];
            }

            //如果是创建表单，配置options['data']以生成创建表单时初始空表单
            if (null == $data) {
                $fieldEntity = $fieldService->getFieldEntity();
                //如果 $fieldType 是 boolean listInteger listFloat 则只生成一个字段
                if (!in_array($fieldType, ['boolean', 'listInteger', 'listFloat'])) {
                    $blankDataArray = [];
                    for ($i = 0; $i < $limit; $i++) {
                        $blankDataArray[$i] = new $fieldEntity();
                    }
                    $baseFieldOptions['data'] = $limit == 0 ? ['0' => new $fieldEntity()] : $blankDataArray;
                } else {
                    $baseFieldOptions['data'] = ['0' => new $fieldEntity()];
                }
            } else {
                //Todo: 解析字段的值并设置$baseFieldOptions['data']
                $fieldData = $fieldService->getFieldEntityData($data, $fieldConfiguration, $options['data_class']);
                dump($fieldData);
                $baseFieldOptions['data'] = $fieldData;
                if ($limit !== 0) {

                }
            }

            //循环添加表单行
            $builder->add($fieldConfiguration->getFieldAlias(),
                BaseFieldType::class,
                $baseFieldOptions);
        }
    }
}