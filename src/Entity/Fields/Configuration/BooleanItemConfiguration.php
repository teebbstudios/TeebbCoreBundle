<?php


namespace Teebb\CoreBundle\Entity\Fields\Configuration;

/**
 * 整型类型字段的设置
 */
class BooleanItemConfiguration extends BaseItemConfiguration
{
    /**
     * doctrine type 用于修改值存储字段在数据库中的类型
     *
     * @var string
     */
    protected $type = 'boolean';

    /**
     * @var string
     */
    protected $on_label;

    /**
     * @var string
     */
    protected $off_label;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getOnLabel(): string
    {
        return $this->on_label;
    }

    /**
     * @param string $on_label
     */
    public function setOnLabel($on_label): void
    {
        $this->on_label = $on_label;
    }

    /**
     * @return string
     */
    public function getOffLabel(): string
    {
        return $this->off_label;
    }

    /**
     * @param string $off_label
     */
    public function setOffLabel($off_label): void
    {
        $this->off_label = $off_label;
    }

}