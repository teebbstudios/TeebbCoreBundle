<?php


namespace Teebb\CoreBundle\Configuration;


abstract class BaseItemConfiguration implements FieldItemDepartConfigurationInterface
{
    /**
     * 字段的标题
     * @var string
     */
    protected $label;

    /**
     * 字段的帮助文本
     * @var string
     */
    protected $description;

    /**
     * 字段是否为必填
     * @var boolean
     */
    protected $required;

    /**
     * 字段数量，如果为-1则表示不限制
     * @var integer
     */
    protected $limit;

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param bool $required
     */
    public function setRequired(bool $required): void
    {
        $this->required = $required;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

}