<?php


namespace Teebb\CoreBundle\Event;


use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;
use Teebb\CoreBundle\Doctrine\Utils\DoctrineUtils;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;

/**
 * @deprecated 使用 classMetadata->mapField() 动态映射字段，
 */
class ModifyClassMetadataEventArgs extends LoadClassMetadataEventArgs
{
    /**
     * @var FieldConfiguration
     */
    private $fieldConfiguration;
    /**
     * @var DoctrineUtils
     */
    private $doctrineUtils;

    public function __construct(ClassMetadata $classMetadata, EntityManager $objectManager,
                                FieldConfiguration $fieldConfiguration, DoctrineUtils $doctrineUtils)
    {
        parent::__construct($classMetadata, $objectManager);
        $this->fieldConfiguration = $fieldConfiguration;
        $this->doctrineUtils = $doctrineUtils;
    }

    /**
     * @return FieldConfiguration
     */
    public function getFieldConfiguration(): FieldConfiguration
    {
        return $this->fieldConfiguration;
    }

    /**
     * @return DoctrineUtils
     */
    public function getDoctrineUtils(): DoctrineUtils
    {
        return $this->doctrineUtils;
    }
}