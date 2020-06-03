<?php


namespace Teebb\CoreBundle\Configuration;

/**
 * 长纯文本类型字段的设置过滤所有HTML标签
 */
class TextItemConfiguration extends BaseItemConfiguration
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