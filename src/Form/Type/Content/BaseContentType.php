<?php


namespace Teebb\CoreBundle\Form\Type\Content;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Teebb\CoreBundle\AbstractService\FieldInterface;
use Teebb\CoreBundle\Entity\BaseContent;
use Teebb\CoreBundle\Entity\Fields\BooleanItem;
use Teebb\CoreBundle\Entity\Fields\CommentItem;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Form\Type\FieldType\BaseFieldType;
use Teebb\CoreBundle\Repository\Fields\FieldConfigurationRepository;

class BaseContentType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var FieldConfigurationRepository
     */
    protected $fieldConfigurationsRepository;
    /**
     * @var ContainerInterface
     */
    protected $container;

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
            ->getBySortableGroupsQuery(['bundle' => $options['bundle'], 'typeAlias' => $options['type_alias']])->getResult();

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
                'entry_type' => $fieldService->getFieldFormType(),
                'entry_options' => [
                    'label' => false,
                    'field_configuration' => $fieldConfiguration,
                    'field_service' => $fieldService
                ],
            ];

            if ($fieldSettings->isRequired()) {
                $baseFieldOptions['constraints'] = [
                    new Count(['min' => 1])
                ];
            }

            //如果是创建表单，配置options['data']以生成创建表单时初始空表单
            $fieldEntityClassName = $fieldService->getFieldEntity();

            if (null == $data) {
                $baseFieldOptions = $this->addNewEntityDataForShowBlankFormRow($limit, $fieldType, $fieldEntityClassName, $baseFieldOptions);
            } else {
                $fieldData = $fieldService->getFieldEntityData($data, $fieldConfiguration, $options['data_class']);

                $baseFieldOptions['data'] = $fieldData;
                //如果当前行字段没有数据则生成空表单行
                if (empty($fieldData)) {
                    $baseFieldOptions = $this->addNewEntityDataForShowBlankFormRow($limit, $fieldType, $fieldEntityClassName, $baseFieldOptions);
                }
            }

            //循环添加表单行
            $builder->add($fieldConfiguration->getFieldAlias(),
                BaseFieldType::class,
                $baseFieldOptions);
        }
    }

    /**
     * @param int $limit 此字段限制的数量
     * @param string $fieldType 字段类型
     * @param string $fieldEntityClassName 字段entity全类名
     * @param array $fieldOptions
     * @param int $needAddNumRows 需要额外增加的字段行数，用于更新表单时空表单的显示
     * @return array
     */
    private function addNewEntityDataForShowBlankFormRow(int $limit, string $fieldType, string $fieldEntityClassName,
                                                         array $fieldOptions, int $needAddNumRows = 0): array
    {
        //如果 $fieldType 是 boolean listInteger listFloat comment 则只生成一个字段
        if (!in_array($fieldType, ['boolean', 'listInteger', 'listFloat', 'comment'])) {
            $blankDataArray = [];
            for ($i = 0; $i < $limit - $needAddNumRows; $i++) {
                $blankDataArray[$i] = new $fieldEntityClassName();
            }
            $fieldOptions['data'] = $limit == 0 ? ['0' => new $fieldEntityClassName()] : $blankDataArray;
        } else {
            $fieldObject = new $fieldEntityClassName();
            //如果是布尔值、评论字段则设置默认值
            switch ($fieldEntityClassName) {
                case BooleanItem::class:
                    /**@var BooleanItem $fieldObject**/
                    $fieldObject->setValue(true);
                    break;
                case CommentItem::class:
                    /**@var CommentItem $fieldObject**/
                    $fieldObject->setValue(1);
                    break;
            }
            $fieldOptions['data'] = ['0' => $fieldObject];
        }

        return $fieldOptions;
    }
}