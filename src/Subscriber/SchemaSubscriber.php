<?php

namespace Teebb\CoreBundle\Subscriber;


use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Teebb\CoreBundle\AbstractService\FieldInterface;
use Teebb\CoreBundle\Doctrine\Utils\DoctrineUtils;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Event\SchemaEvent;
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
     * @throws ToolsException
     */
    public function onCreateSchemaEvent(SchemaEvent $event)
    {
        /**@var FieldConfiguration $fieldConfiguration * */
        $fieldConfiguration = $event->getSubject();
        $entityClassName = $event->getContentEntity();

        $classMetadata = $this->getFieldEntityClassMetaData($fieldConfiguration, $entityClassName);
        $classMetadata->setPrimaryTable(['name' => $fieldConfiguration->getBundle() . '__field_' . $fieldConfiguration->getFieldAlias()]);

        $this->doctrineUtils->createSchema([$classMetadata]);
    }

    /**
     * @param SchemaEvent $event
     */
    public function onDropSchemaEvent(SchemaEvent $event)
    {
        /**@var FieldConfiguration $fieldConfiguration * */
        $fieldConfiguration = $event->getSubject();
        $entityClassName = $event->getContentEntity();

        $classMetadata = $this->getFieldEntityClassMetaData($fieldConfiguration, $entityClassName);
        $classMetadata->setPrimaryTable(['name' => $fieldConfiguration->getBundle() . '__field_' . $fieldConfiguration->getFieldAlias()]);

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
     */
    private function getFieldEntityClassMetaData(FieldConfiguration $fieldConfiguration, string $entityClassName): ClassMetadata
    {
        /**@var FieldInterface $fieldService * */
        $fieldService = $this->getFieldService($fieldConfiguration->getFieldType());

        /**@var DynamicChangeFieldMetadataSubscriber $subscriber**/
        $subscriber = $this->container->get('teebb.core.event.dynamic_field_mapping_subscriber');
        $subscriber->setFieldConfiguration($fieldConfiguration);
        $subscriber->setTargetContentClassName($entityClassName);

        $fieldEntity = $fieldService->getFieldEntity();
        return $this->doctrineUtils->getSingleClassMetadata($fieldEntity);
    }
}