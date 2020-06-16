<?php


namespace Teebb\CoreBundle\Entity\Fields\Configuration;


class ReferenceContentItemConfiguration extends BaseItemConfiguration
{
    /**
     * 如果类型为 entity 则不处理doctrine metadata
     *
     * @var string
     */
    protected $type = 'entity';

    /**
     * 要引用的内容类型别名数组
     * @var array
     */
    protected $referenceTypes;

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

}