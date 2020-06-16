<?php


namespace Teebb\CoreBundle\Entity\Fields\Configuration;

/**
 * 浮点类型字段的设置
 */
class DecimalItemConfiguration extends BaseItemConfiguration
{
    /**
     * doctrine type 用于修改值存储字段在数据库中的类型
     *
     * @var string
     */
    protected $type = 'decimal';

    /**
     * DECIMAL精度，在数据库中存储的数字的总位数，包括小数点右边的位数。
     *
     * @var integer
     */
    protected $precision;

    /**
     * 小数点后面的位数。
     *
     * @var integer
     */
    protected $scale;

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
     * @return int
     */
    public function getPrecision(): int
    {
        return $this->precision;
    }

    /**
     * @param int $precision
     */
    public function setPrecision(int $precision): void
    {
        $this->precision = $precision;
    }

    /**
     * @return int
     */
    public function getScale(): int
    {
        return $this->scale;
    }

    /**
     * @param int $scale
     */
    public function setScale(int $scale): void
    {
        $this->scale = $scale;
    }

    /**
     * @return float
     */
    public function getMin(): float
    {
        return $this->min;
    }

    /**
     * @param float $min
     */
    public function setMin(float $min): void
    {
        $this->min = $min;
    }

    /**
     * @return float
     */
    public function getMax(): float
    {
        return $this->max;
    }

    /**
     * @param float $max
     */
    public function setMax(float $max): void
    {
        $this->max = $max;
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix(string $prefix): void
    {
        $this->prefix = $prefix;
    }

    /**
     * @return string
     */
    public function getSuffix(): string
    {
        return $this->suffix;
    }

    /**
     * @param string $suffix
     */
    public function setSuffix(string $suffix): void
    {
        $this->suffix = $suffix;
    }

}