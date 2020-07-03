<?php


namespace Teebb\CoreBundle\AbstractService;


use Teebb\CoreBundle\Metadata\FieldMetadataInterface;

/**
 * Class AbstractField
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
abstract class AbstractField implements FieldInterface
{
    /**
     * @var FieldMetadataInterface
     */
    protected $metadata;

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

    /**
     * 获取字段Entity类名
     * @return string
     */
    public function getFieldEntity(): string
    {
        return $this->metadata->getEntity();
    }

    /**
     * 获取字段设置表单Entity全类名
     * @return string
     */
    public function getFieldConfigFormEntity(): string
    {
        return $this->metadata->getFieldFormConfigEntity();
    }

    /**
     * 获取字段设置表单Type全类名
     * @return string
     */
    public function getFieldConfigFormType(): string
    {
        return $this->metadata->getFieldFormConfigType();
    }

    /**
     * @inheritDoc
     */
    public function getFieldFormType(): string
    {
        return $this->metadata->getFieldFormType();
    }
}