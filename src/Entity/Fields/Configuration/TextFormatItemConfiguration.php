<?php


namespace Teebb\CoreBundle\Entity\Fields\Configuration;

/**
 * 长文本已格式化类型字段的设置,允许HTML标签
 */
class TextFormatItemConfiguration extends BaseItemConfiguration
{
    /**
     * doctrine type 用于修改值存储字段在数据库中的类型
     *
     * @var string
     */
    protected $type = 'text';

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}