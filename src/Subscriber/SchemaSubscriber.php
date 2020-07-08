<?php

namespace Teebb\CoreBundle\Subscriber;


use Doctrine\ORM\Events;
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
use Teebb\CoreBundle\Listener\DynamicChangeFieldMetadataListener;

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
     */
    private function getFieldEntityClassMetaData(FieldConfiguration $fieldConfiguration, string $entityClassName): ClassMetadata
    {
        /**@var FieldInterface $fieldService * */
        $fieldService = $this->getFieldService($fieldConfiguration->getFieldType());

        $evm = $this->doctrineUtils->getEventManager();
        //动态修改字段entity的mapping
        $dynamicChangeFieldMetadataListener = new DynamicChangeFieldMetadataListener($fieldConfiguration, $entityClassName);
        $evm->addEventListener(Events::loadClassMetadata, $dynamicChangeFieldMetadataListener);

        $fieldEntity = $fieldService->getFieldEntity();
        $classMetadata = $this->doctrineUtils->getSingleClassMetadata($fieldEntity);

        $evm->removeEventListener(Events::loadClassMetadata, $dynamicChangeFieldMetadataListener);

        return $classMetadata;
    }
}