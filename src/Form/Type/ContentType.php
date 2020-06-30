<?php


namespace Teebb\CoreBundle\Form\Type;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
            ->getBySortableGroupsQuery([
                'bundle' => $options['bundle'],
                'typeAlias' => $options['type_alias']
            ])->getResult();

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
                'allow_add' => true,
                'allow_delete' => true,
                'entry_type' => $fieldService->getFieldFormType(),
                'entry_options' => [
                    'label' => false,
                    'field_configuration' => $fieldConfiguration,
                    'field_service' => $fieldService,
                ],
            ];

            //配置options['data']以生成创建表单时初始空表单
            if (null == $data) {
                $fieldEntity = $fieldService->getFieldEntity();

                $blankDataArray = [];
                for ($i = 0; $i < $limit; $i++) {
                    $blankDataArray[$i] = new $fieldEntity();
                }

                $options['data'] = $limit == 0 ? ['0' => new $fieldEntity()] : $blankDataArray;
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