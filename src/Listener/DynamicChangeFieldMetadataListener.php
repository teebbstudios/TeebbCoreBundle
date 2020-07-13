<?php


namespace Teebb\CoreBundle\Listener;


use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\MappingException;
use Teebb\CoreBundle\Entity\Fields\BooleanItem;
use Teebb\CoreBundle\Entity\Fields\Configuration\FieldDepartConfigurationInterface;
use Teebb\CoreBundle\Entity\Fields\DatetimeItem;
use Teebb\CoreBundle\Entity\Fields\DecimalItem;
use Teebb\CoreBundle\Entity\Fields\EmailItem;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Entity\Fields\FloatItem;
use Teebb\CoreBundle\Entity\Fields\IntegerItem;
use Teebb\CoreBundle\Entity\Fields\LinkItem;
use Teebb\CoreBundle\Entity\Fields\ListFloatItem;
use Teebb\CoreBundle\Entity\Fields\ListIntegerItem;
use Teebb\CoreBundle\Entity\Fields\ReferenceContentItem;
use Teebb\CoreBundle\Entity\Fields\ReferenceEntityItem;
use Teebb\CoreBundle\Entity\Fields\ReferenceFileItem;
use Teebb\CoreBundle\Entity\Fields\ReferenceImageItem;
use Teebb\CoreBundle\Entity\Fields\ReferenceTaxonomyItem;
use Teebb\CoreBundle\Entity\Fields\ReferenceUserItem;
use Teebb\CoreBundle\Entity\Fields\SimpleFormatItem;
use Teebb\CoreBundle\Entity\Fields\SimpleValueItem;
use Teebb\CoreBundle\Entity\Fields\StringFormatItem;
use Teebb\CoreBundle\Entity\Fields\StringItem;
use Teebb\CoreBundle\Entity\Fields\TextFormatItem;
use Teebb\CoreBundle\Entity\Fields\TextFormatSummaryItem;
use Teebb\CoreBundle\Entity\Fields\TextItem;
use Teebb\CoreBundle\Entity\Fields\TimestampItem;

class DynamicChangeFieldMetadataListener
{
    /**
     * @var FieldConfiguration
     */
    private $fieldConfiguration;
    /**
     * @var string
     */
    private $targetContentClassName;

    public function __construct(FieldConfiguration $fieldConfiguration, string $targetContentClassName)
    {
        $this->fieldConfiguration = $fieldConfiguration;
        $this->targetContentClassName = $targetContentClassName;
    }

    /**
     * @param LoadClassMetadataEventArgs $args
     * @throws MappingException
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    {
        $classMetadata = $args->getClassMetadata();
        $className = $classMetadata->getName();

        //如果 $className 是以下类型则进行动态字段映射修改
        $modifyEntityIemArray = [
            BooleanItem::class,
            DatetimeItem::class,
            DecimalItem::class,
            EmailItem::class,
            FloatItem::class,
            IntegerItem::class,
            LinkItem::class,
            ListFloatItem::class,
            ListIntegerItem::class,
            ReferenceContentItem::class,
            ReferenceFileItem::class,
            ReferenceImageItem::class,
            ReferenceTaxonomyItem::class,
            ReferenceUserItem::class,
            StringFormatItem::class,
            StringItem::class,
            TextFormatItem::class,
            TextFormatSummaryItem::class,
            TextItem::class,
            TimestampItem::class
        ];

        if (in_array($className, $modifyEntityIemArray)) {
            $this->modifyFieldEntityClassMetaData($classMetadata, $this->fieldConfiguration, $this->targetContentClassName);
        }
    }

    /**
     * 动态修改ClassMetadata
     * @param ClassMetadata $classMetadata
     * @param FieldConfiguration $fieldConfiguration
     * @param string $entityClassName content entity全类名
     * @throws MappingException
     */
    private function modifyFieldEntityClassMetaData(ClassMetadata $classMetadata, FieldConfiguration $fieldConfiguration, string $entityClassName)
    {
        //设置字段表名
        $fieldAlias = $fieldConfiguration->getFieldAlias();
        $classMetadata->setPrimaryTable(['name' => $fieldConfiguration->getBundle() . '__field_' . $fieldAlias]);

        /**@var FieldDepartConfigurationInterface $fieldDepartConfiguration * */
        $fieldDepartConfiguration = $fieldConfiguration->getSettings();

        $doctrineType = $fieldDepartConfiguration->getType();

        //映射字段的entity属性
        if (!$classMetadata->hasAssociation('entity')) {
            $classMetadata->mapManyToOne([
                'fieldName' => 'entity',
                'targetEntity' => $entityClassName,
                'cascade' => ['remove', 'persist'],
                'joinColumns' => [
                    [
                        'name' => 'entity_id',
                        'referencedColumnName' => 'id',
                        'nullable' => false,
                    ]
                ]
            ]);
        }

        //处理引用内容、分类、用户类型的value属性mapping
        if ($doctrineType === 'entity' && !$classMetadata->hasAssociation('value')) {

            if (!method_exists($fieldDepartConfiguration, 'getReferenceTargetEntity')) {
                throw new \RuntimeException(sprintf('Reference field configuration "%s" must define "getReferenceTargetEntity" method.', get_class($fieldDepartConfiguration)));
            }

            $classMetadata->mapManyToOne([
                'fieldName' => 'value',
                'targetEntity' => $fieldDepartConfiguration->getReferenceTargetEntity(),
                'cascade' => ['remove', 'persist'],
                'joinColumns' => [
                    [
                        'name' => 'reference_entity_id',
                        'referencedColumnName' => 'id',
                        'nullable' => false,
                    ]
                ]
            ]);
        }

        //对于非引用类型value属性的映射
        if ($doctrineType !== 'entity' && !$classMetadata->hasField('value')) {
            //动态Mapping字段value,
            $fieldMapping = array(
                'fieldName' => 'value',
                'columnName' => 'field_' . $fieldAlias . '_value',
                'type' => $doctrineType,
                'nullable' => true
            );

            //字段的Entity全类名
            $fieldEntityClassName = $classMetadata->getName();
            if (in_array($fieldEntityClassName, [StringFormatItem::class, StringItem::class])) {
                //如果是文本类型需要设置数据库length
                if (method_exists($fieldDepartConfiguration, 'getLength')) {
                    $fieldMapping['length'] = $fieldDepartConfiguration->getLength();
                }
            }
            if (in_array($fieldEntityClassName, [DecimalItem::class])) {
                //如果是小数类型则需要添加precision，scale
                if ($doctrineType == 'decimal') {
                    $fieldMapping['precision'] = $fieldDepartConfiguration->getPrecision();
                    $fieldMapping['scale'] = $fieldDepartConfiguration->getScale();
                }
            }

            $classMetadata->mapField($fieldMapping);
        }
    }
}