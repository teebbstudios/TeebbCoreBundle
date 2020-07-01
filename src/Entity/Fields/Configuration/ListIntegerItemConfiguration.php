<?php


namespace Teebb\CoreBundle\Entity\Fields\Configuration;

/**
 * 列表整型类型字段的设置
 */
class ListIntegerItemConfiguration extends BaseItemConfiguration
{
    /**
     * doctrine type 用于修改值存储字段在数据库中的类型
     *
     * @var string
     */
    protected $type = 'array';

    /**
     * @var array
     */
    protected $allowed_values;

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array|null
     */
    public function getAllowedValues(): ?array
    {
        return $this->allowed_values;
    }

    /**
     * @param array $allowed_values
     */
    public function setAllowedValues(array $allowed_values): void
    {
        $this->allowed_values = $allowed_values;
    }

}