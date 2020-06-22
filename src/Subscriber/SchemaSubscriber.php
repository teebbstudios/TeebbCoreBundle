<?php


namespace Teebb\CoreBundle\Subscriber;


use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Teebb\CoreBundle\Event\ModifyClassMetadataEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Teebb\CoreBundle\AbstractService\FieldInterface;
use Teebb\CoreBundle\Doctrine\Utils\DoctrineUtils;
use Teebb\CoreBundle\Entity\Fields\Configuration\FieldDepartConfigurationInterface;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Entity\Fields\ReferenceEntityItem;
use Teebb\CoreBundle\Entity\Fields\SimpleFormatItem;
use Teebb\CoreBundle\Entity\Fields\SimpleValueItem;
use Teebb\CoreBundle\Event\SchemaEvent;

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

    public function onCreateSchemaEvent(SchemaEvent $event)
    {
        /**@var FieldConfiguration $fieldConfiguration * */
        $fieldConfiguration = $event->getSubject();

        /**@var FieldInterface $fieldService * */
        $fieldService = $this->getFieldService($fieldConfiguration->getFieldType());

        $fieldEntity = $fieldService->getFieldEntity();

        $evm = $this->doctrineUtils->getEventManager();

        $classmate = new ClassMetadata($fieldEntity);
        $em = $this->doctrineUtils->getEntityManager();
        $args = new ModifyClassMetadataEventArgs($classmate,$em,$fieldConfiguration,$this->doctrineUtils);
//        $args = new LoadClassMetadataEventArgs($classmate,$em);
        $evm->dispatchEvent(Events::loadClassMetadata, $args);

        $classmate = $this->doctrineUtils->getSingleClassMetadata($fieldEntity);

        //设置字段表名
        $fieldAlias = $fieldConfiguration->getFieldAlias();
        $classmate->setPrimaryTable(['name' => $fieldConfiguration->getBundle() . '__field_' . $fieldAlias]);
        $classmate->fieldMappings['value']['type'] = 'integer';

        switch ($fieldEntity) {
            case SimpleValueItem::class:

                //如果是普通文本类型需要设置数据库length
                /**@var FieldDepartConfigurationInterface $fieldDepartConfiguration * */
                $fieldDepartConfiguration = $fieldConfiguration->getSettings();

                if (method_exists($fieldDepartConfiguration, 'getLength')) {
                    $overrideMapping['length'] = $fieldDepartConfiguration->getLength();
                }

                $overrideMapping['type'] = $doctrineType = $fieldDepartConfiguration->getType();
                if ($doctrineType == 'decimal') {
                    $overrideMapping['precision'] = $fieldDepartConfiguration->getPrecision();
                    $overrideMapping['scale'] = $fieldDepartConfiguration->getScale();
                }

                break;
            case SimpleFormatItem::class:

                break;

            case ReferenceEntityItem::class:
                break;
        }

        $this->doctrineUtils->createSchema([$classmate]);

    }

    public function onDropSchemaEvent(SchemaEvent $event)
    {
        dd(2, $event->getSubject());
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
}