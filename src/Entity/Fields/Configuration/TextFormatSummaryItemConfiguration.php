<?php


namespace Teebb\CoreBundle\Entity\Fields\Configuration;

/**
 * 长文本已格式化带摘要类型字段的设置,允许HTML标签
 */
class TextFormatSummaryItemConfiguration extends BaseItemConfiguration
{
    /**
     * doctrine type 用于修改值存储字段在数据库中的类型
     *
     * @var string
     */
    protected $type = 'text';

    /**
     * 是否显示摘要输入框，如果不显示则自动生成摘要
     *
     * @var bool
     */
    protected $showSummaryInput = true;

    /**
     * 摘要是否为必填
     *
     * @var bool
     */
    protected $summaryRequired = false;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isShowSummaryInput(): bool
    {
        return $this->showSummaryInput;
    }

    /**
     * @param bool $showSummaryInput
     */
    public function setShowSummaryInput(bool $showSummaryInput): void
    {
        $this->showSummaryInput = $showSummaryInput;
    }

    /**
     * @return bool
     */
    public function isSummaryRequired(): bool
    {
        return $this->summaryRequired;
    }

    /**
     * @param bool $summaryRequired
     */
    public function setSummaryRequired(bool $summaryRequired): void
    {
        $this->summaryRequired = $summaryRequired;
    }

}