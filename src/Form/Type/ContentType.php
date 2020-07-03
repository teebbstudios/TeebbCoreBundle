<?php


namespace Teebb\CoreBundle\Form\Type;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Teebb\CoreBundle\AbstractService\FieldInterface;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Form\Type\FieldType\BaseFieldType;
use Teebb\CoreBundle\Repository\Fields\FieldConfigurationRepository;

class ContentType extends AbstractType
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

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $builder->getData();

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

            $options = [
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
                    'field_service' => $fieldService,
                ],
            ];

            if ($fieldSettings->isRequired()) {
                $options['constraints'] = [
                    new Count(['min' => 1])
                ];
            }

            //配置options['data']以生成创建表单时初始空表单
            //如果 $fieldType 是 boolean listInteger listFloat 则只生成一个字段
            $fieldEntity = $fieldService->getFieldEntity();
            if (!in_array($fieldType, ['boolean', 'listInteger', 'listFloat'])) {
                if (null == $data) {
                    $blankDataArray = [];
                    for ($i = 0; $i < $limit; $i++) {
                        $blankDataArray[$i] = new $fieldEntity();
                    }
                    $options['data'] = $limit == 0 ? ['0' => new $fieldEntity()] : $blankDataArray;
                }
            } else {
                $options['data'] = ['0' => new $fieldEntity()];
            }

            //循环添加表单行
            $builder->add($fieldConfiguration->getFieldAlias(),
                BaseFieldType::class,
                $options);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
        ]);

        $resolver->setDefined('bundle');
        $resolver->setDefined('type_alias');

        $resolver->setRequired('bundle');
        $resolver->setRequired('type_alias');

        $resolver->setAllowedTypes('bundle', 'string');
        $resolver->setAllowedTypes('type_alias', 'string');
    }

}