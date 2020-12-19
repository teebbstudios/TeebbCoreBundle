<?php


namespace Teebb\CoreBundle\Entity\Fields\Configuration;

/**
 * 浮点类型字段的设置
 */
class FloatItemConfiguration extends BaseItemConfiguration
{
    /**
     * doctrine type 用于修改值存储字段在数据库中的类型
     *
     * @var string
     */
    protected $type = 'float';

    /**
     * @var float
     */
    protected $min;

    /**
     * @var float
     */
    protected $max;

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var string
     */
    protected $suffix;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return float|null
     */
    public function getMin(): ?float
    {
        return $this->min;
    }

    /**
     * @param float|null $min
     */
    public function setMin(?float $min): void
    {
        $this->min = $min;
    }

    /**
     * @return float|null
     */
    public function getMax(): ?float
    {
        return $this->max;
    }

    /**
     * @param float|null $max
     */
    public function setMax(?float $max): void
    {
        $this->max = $max;
    }

    /**
     * @return string|null
     */
    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    /**
     * @param string|null $prefix
     */
    public function setPrefix(?string $prefix): void
    {
        $this->prefix = $prefix;
    }

    /**
     * @return string|null
     */
    public function getSuffix(): ?string
    {
        return $this->suffix;
    }

    /**
     * @param string|null $suffix
     */
    public function setSuffix(?string $suffix): void
    {
        $this->suffix = $suffix;
    }
    
}