<?php


namespace Teebb\CoreBundle\Form\Type;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Teebb\CoreBundle\AbstractService\FieldInterface;
use Teebb\CoreBundle\Entity\BaseContent;
use Teebb\CoreBundle\Entity\Fields\Configuration\StringItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\Configuration\TextFormatSummaryItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Form\Type\FieldType\BooleanFieldType;
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

            $fieldOptions = $this->getFieldsOptions($fieldType, $fieldConfiguration, $fieldService, $data);

            $fieldSettings = $fieldConfiguration->getSettings();
            //动态修改表单行 options
            $options = [
                'label' => $fieldConfiguration->getFieldLabel(),
                'help' => $fieldSettings->getDescription(),
                'required' => $fieldSettings->isRequired(),
                'limit' => $fieldSettings->getLimit(),
                'field_configuration' => $fieldConfiguration,
                'field_service' => $fieldService
            ];

            //循环添加表单行
            $builder->add($fieldConfiguration->getFieldAlias(),
                $fieldService->getFieldFormType(),
                array_merge_recursive($fieldOptions, $options));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined('bundle');
        $resolver->setDefined('type_alias');

        $resolver->setRequired('bundle');
        $resolver->setRequired('type_alias');

        $resolver->setAllowedTypes('bundle', 'string');
        $resolver->setAllowedTypes('type_alias', 'string');
    }

    /**
     * 获取不同字段表单Type的options
     *
     * @param string $fieldType
     * @param FieldConfiguration $fieldConfiguration
     * @param FieldInterface $fieldService
     * @param BaseContent $content
     * @return array
     */
    private function getFieldsOptions(string $fieldType, FieldConfiguration $fieldConfiguration,
                                      FieldInterface $fieldService, BaseContent $content = null): array
    {
        $fieldSettings = $fieldConfiguration->getSettings();
        $options = [];
        switch ($fieldType) {
            case 'boolean':

                break;
            case 'datetime':
                break;
            case 'decimal':
                break;
            case 'email':
                break;
            case 'float':
                break;
            case 'integer':
                break;
            case 'link':
                break;
            case 'listFloat':
                break;
            case 'listInteger':
                break;
            case 'referenceContent':
                break;
            case 'referenceFile':
                break;
            case 'referenceImage':
                break;
            case 'referenceTaxonomy':
                break;
            case 'referenceUser':
                break;
            case 'string':
                /**@var StringItemConfiguration $fieldSettings **/
                if ($fieldSettings->isRequired()) {
                    $options['constraints'] = [
                        new NotBlank(),
                        new Length([
                            'min' => 1,
                            'max' => $fieldSettings->getLength()
                        ])
                    ];
                }
                $options['attr'] = [
                    'class' => 'form-control form-control-sm col-12 col-sm-6',
                    'maxlength' => $fieldSettings->getLength()
                ];
                break;
            case 'stringFormat':
                break;
            case 'text':
                break;
            case 'textFormat':
                break;
            case 'textFormatSummary':
                /**@var TextFormatSummaryItemConfiguration $fieldSettings **/
                if ($fieldSettings->isRequired()) {
                    $options['constraints'] = [
                        new NotBlank(),
                    ];
                }
                $options['show_summary'] = $fieldSettings->isShowSummaryInput();
                $options['summary_required'] = $fieldSettings->isSummaryRequired();
                break;
            case 'timestamp':
                break;
        }
        return $options;
    }
}