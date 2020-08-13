<?php


namespace Teebb\CoreBundle\Entity\Fields\Configuration;


abstract class BaseItemConfiguration implements FieldDepartConfigurationInterface
{
    /**
     * 字段的帮助文本
     * @var string
     */
    protected $description;

    /**
     * 字段是否为必填
     * @var boolean
     */
    protected $required = false;

    /**
     * 字段数量，如果为-1则表示不限制
     * @var integer
     */
    protected $limit = 1;

    /**
     * 是否显示字段标题
     * @var bool
     */
    protected $showLabel = true;

    /**
     * @return string|null
     */
    public function getDescription(): ?string
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

    /**
     * @return bool
     */
    public function isShowLabel(): bool
    {
        return $this->showLabel;
    }

    /**
     * @param bool $showLabel
     */
    public function setShowLabel(bool $showLabel): void
    {
        $this->showLabel = $showLabel;
    }
}