<?php

namespace Teebb\CoreBundle\Subscriber;


use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Teebb\CoreBundle\AbstractService\FieldInterface;
use Teebb\CoreBundle\Doctrine\Utils\DoctrineUtils;
use Teebb\CoreBundle\Entity\Fields\Configuration\FieldDepartConfigurationInterface;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Entity\Fields\SimpleFormatItem;
use Teebb\CoreBundle\Entity\Fields\SimpleValueItem;
use Teebb\CoreBundle\Event\SchemaEvent;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\Tools\ToolsException;

/**
 * 用于动态生成或删除字段表
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class SchemaSubscriber implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var DoctrineUtils
     */
    private $doctrineUtils;

    public function __construct(ContainerInterface $container, DoctrineUtils $doctrineUtils)
    {
        $this->container = $container;
        $this->doctrineUtils = $doctrineUtils;
    }

    public static function getSubscribedEvents()
    {
        return [
            SchemaEvent::CREATE_SCHEMA => 'onCreateSchemaEvent',
            SchemaEvent::DROP_SCHEMA => 'onDropSchemaEvent',
        ];
    }

    /**
     * @param SchemaEvent $event
     * @throws MappingException
     * @throws ToolsException
     */
    public function onCreateSchemaEvent(SchemaEvent $event)
    {
        /**@var FieldConfiguration $fieldConfiguration * */
        $fieldConfiguration = $event->getSubject();
        $entityClassName = $event->getContentEntity();

        $classMetadata = $this->getFieldEntityClassMetaData($fieldConfiguration, $entityClassName);

        $this->doctrineUtils->createSchema([$classMetadata]);
    }

    /**
     * @param SchemaEvent $event
     * @throws MappingException
     */
    public function onDropSchemaEvent(SchemaEvent $event)
    {
        /**@var FieldConfiguration $fieldConfiguration * */
        $fieldConfiguration = $event->getSubject();
        $entityClassName = $event->getContentEntity();

        $classMetadata = $this->getFieldEntityClassMetaData($fieldConfiguration, $entityClassName);

        $this->doctrineUtils->dropSchema([$classMetadata]);
    }

    /**
     * 获取字段service
     *
     * @param string $fieldType
     * @return object|null
     */
    private function getFieldService(string $fieldType)
    {
        return $this->container->get('teebb.core.field.' . $fieldType);
    }

    /**
     * @param FieldConfiguration $fieldConfiguration
     * @param string $entityClassName content entity全类名
     * @return ClassMetadata
     * @throws MappingException
     */
    private function getFieldEntityClassMetaData(FieldConfiguration $fieldConfiguration, string $entityClassName): ClassMetadata
    {
        /**@var FieldInterface $fieldService * */
        $fieldService = $this->getFieldService($fieldConfiguration->getFieldType());

        $fieldEntity = $fieldService->getFieldEntity();
        $classMetadata = $this->doctrineUtils->getSingleClassMetadata($fieldEntity);

        //设置字段表名
        $fieldAlias = $fieldConfiguration->getFieldAlias();
        $classMetadata->setPrimaryTable(['name' => $fieldConfiguration->getBundle() . '__field_' . $fieldAlias]);

        /**@var FieldDepartConfigurationInterface $fieldDepartConfiguration * */
        $fieldDepartConfiguration = $fieldConfiguration->getSettings();

        $doctrineType = $fieldDepartConfiguration->getType();

        //mapping字段entity
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

        //处理引用类型的value属性mapping
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
                'type' => $doctrineType,
                'nullable' => false
            );

            switch ($fieldEntity) {
                case SimpleValueItem::class:
                    //如果是文本类型需要设置数据库length
                    if (method_exists($fieldDepartConfiguration, 'getLength')) {
                        $fieldMapping['length'] = $fieldDepartConfiguration->getLength();
                    }

                    //如果是小数类型则需要添加precision，scale
                    if ($doctrineType == 'decimal') {
                        $fieldMapping['precision'] = $fieldDepartConfiguration->getPrecision();
                        $fieldMapping['scale'] = $fieldDepartConfiguration->getScale();
                    }
                    break;

                case SimpleFormatItem::class:
                    //如果是文本类型需要设置数据库length
                    if (method_exists($fieldDepartConfiguration, 'getLength')) {
                        $fieldMapping['length'] = $fieldDepartConfiguration->getLength();
                    }
                    break;
            }

            $classMetadata->mapField($fieldMapping);
        }

        return $classMetadata;
    }
}