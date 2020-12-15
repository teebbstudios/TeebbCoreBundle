<?php


namespace Teebb\CoreBundle\Event;


use Teebb\CoreBundle\AbstractService\AbstractField;
use Teebb\CoreBundle\Entity\BaseContent;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;

/**
 * 各字段数据缓存事件
 */
class DataCacheEvent
{
    //生成字段缓存
    public const GET_FIELD_CACHE = 'get.field.cache';
    //删除字段缓存
    public const DELETE_FIELD_CACHE = 'delete.field.cache';

    /**
     * @var BaseContent
     */
    private $baseContent;

    /**
     * @var AbstractField
     */
    private $fieldService;

    /**
     * @var FieldConfiguration
     */
    private $fieldConfiguration;
    /**
     * @var string
     */
    private $targetEntityClassName;

    /**
     * @var array
     */
    private $needDeleteCacheKeyArray;

    /**
     * @return BaseContent
     */
    public function getBaseContent(): BaseContent
    {
        return $this->baseContent;
    }

    /**
     * @param BaseContent $baseContent
     */
    public function setBaseContent(BaseContent $baseContent): void
    {
        $this->baseContent = $baseContent;
    }

    /**
     * @return AbstractField
     */
    public function getFieldService(): AbstractField
    {
        return $this->fieldService;
    }

    /**
     * @param AbstractField $fieldService
     */
    public function setFieldService(AbstractField $fieldService): void
    {
        $this->fieldService = $fieldService;
    }

    /**
     * @return FieldConfiguration
     */
    public function getFieldConfiguration(): FieldConfiguration
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
     * @return string
     */
    public function getTargetEntityClassName(): string
    {
        return $this->targetEntityClassName;
    }

    /**
     * @param string $targetEntityClassName
     */
    public function setTargetEntityClassName(string $targetEntityClassName): void
    {
        $this->targetEntityClassName = $targetEntityClassName;
    }

    /**
     * @return array
     */
    public function getNeedDeleteCacheKeyArray(): array
    {
        return $this->needDeleteCacheKeyArray;
    }

    /**
     * @param array $needDeleteCacheKeyArray
     */
    public function setNeedDeleteCacheKeyArray(array $needDeleteCacheKeyArray): void
    {
        $this->needDeleteCacheKeyArray = $needDeleteCacheKeyArray;
    }

}