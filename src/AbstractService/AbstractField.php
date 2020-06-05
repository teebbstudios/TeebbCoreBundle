<?php


namespace Teebb\CoreBundle\AbstractService;


use Doctrine\ORM\EntityManagerInterface;
use Teebb\CoreBundle\Metadata\FieldMetadataInterface;

abstract class AbstractField implements FieldInterface
{
    /**
     * @var FieldMetadataInterface
     */
    protected $metadata;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritDoc
     */
    public function getFieldId(): string
    {
        if (null == $this->metadata) {
            throw new \Exception(sprintf('The field service "%s" $metadata did not set.', self::class));
        }
        return $this->metadata->getId();
    }

    /**
     * @inheritDoc
     */
    public function setFieldMetadata(FieldMetadataInterface $metadata): void
    {
        $this->metadata = $metadata;
    }

    /**
     * @inheritDoc
     */
    public function getFieldMetadata(): FieldMetadataInterface
    {
        return $this->metadata;
    }


}