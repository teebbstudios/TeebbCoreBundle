<?php


namespace Teebb\CoreBundle\Subscriber;


use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\MappingException;
use Teebb\CoreBundle\Entity\Fields\BooleanItem;
use Teebb\CoreBundle\Entity\Fields\CommentItem;
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
use Teebb\CoreBundle\Entity\Fields\ReferenceFileItem;
use Teebb\CoreBundle\Entity\Fields\ReferenceImageItem;
use Teebb\CoreBundle\Entity\Fields\ReferenceTaxonomyItem;
use Teebb\CoreBundle\Entity\Fields\ReferenceUserItem;
use Teebb\CoreBundle\Entity\Fields\StringFormatItem;
use Teebb\CoreBundle\Entity\Fields\StringItem;
use Teebb\CoreBundle\Entity\Fields\TextFormatItem;
use Teebb\CoreBundle\Entity\Fields\TextFormatSummaryItem;
use Teebb\CoreBundle\Entity\Fields\TextItem;

class DynamicChangeFieldMetadataSubscriber implements EventSubscriber
{
    /**
     * @var FieldConfiguration
     */
    private $fieldConfiguration;
    /**
     * @var string
     */
    private $targetContentClassName;

    /**
     * @return FieldConfiguration|null
     */
    public function getFieldConfiguration(): ?FieldConfiguration
    {
        return $this->fieldConfiguration;
    }

    /**
     * @param FieldConfiguration $fieldConfiguration
     */
    public function setFieldConfiguration(FieldConfiguration $fieldConfiguration): void
    {
        $this->fieldConfiguration = $fieldConfiguration;
    }

    /**
     * @return string|null
     */
    public function getTargetContentClassName(): ?string
    {
        return $this->targetContentClassName;
    }

    /**
     * @param string $targetContentClassName
     */
    public function setTargetContentClassName(string $targetContentClassName): void
    {
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
            CommentItem::class
        ];

        if (in_array($className, $modifyEntityIemArray)) {
            $this->modifyFieldEntityClassMetaData($classMetadata, $this->getFieldConfiguration(), $this->getTargetContentClassName());
        }
    }

    /**
     * 动态修改ClassMetadata
     * @param ClassMetadata $classMetadata
     * @param FieldConfiguration|null $fieldConfiguration
     * @param string|null $entityClassName content entity全类名
     * @throws MappingException
     */
    private function modifyFieldEntityClassMetaData(ClassMetadata $classMetadata, ?FieldConfiguration $fieldConfiguration, ?string $entityClassName)
    {
        if ($fieldConfiguration == null || $entityClassName == null) {
            return;
        }
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
                        'nullable' => true,
                    ]
                ]
            ]);
        }

        //对于非引用类型value属性的映射
        if ($doctrineType !== 'entity' && !$classMetadata->hasField('value')) {
            //动态Mapping字段value,
            $fieldMapping = array(
                'fieldName' => 'value',
                'columnName' => 'field_value',
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

    public function getSubscribedEvents(): array
    {
        return [
            Events::loadClassMetadata,
        ];
    }
}