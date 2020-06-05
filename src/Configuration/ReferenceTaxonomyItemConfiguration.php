<?php


namespace Teebb\CoreBundle\Configuration;


class ReferenceTaxonomyItemConfiguration extends BaseItemConfiguration
{
    /**
     * 如果类型为 entity 则不处理doctrine metadata
     *
     * @var string
     */
    protected $type = 'entity';

    /**
     * 要引用的taxonomy类型别名数组
     * @var array
     */
    protected $referenceTypes;

    /**
     * 如果标签不存在则添加标签到某个Taxonomy类型
     * @var string
     */
    protected $autoCreateToType;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getReferenceTypes(): array
    {
        return $this->referenceTypes;
    }

    /**
     * @param array $referenceTypes
     */
    public function setReferenceTypes(array $referenceTypes): void
    {
        $this->referenceTypes = $referenceTypes;
    }

    /**
     * @return string
     */
    public function getAutoCreateToType(): string
    {
        return $this->autoCreateToType;
    }

    /**
     * @param string $autoCreateToType
     */
    public function setAutoCreateToType(string $autoCreateToType): void
    {
        $this->autoCreateToType = $autoCreateToType;
    }
    
}