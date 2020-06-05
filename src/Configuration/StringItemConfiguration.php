<?php


namespace Teebb\CoreBundle\Configuration;

/**
 * 纯文本类型字段的设置，过滤所有HTML标签
 */
class StringItemConfiguration extends BaseItemConfiguration
{
    /**
     * doctrine type 用于修改值存储字段在数据库中的类型
     *
     * @var string
     */
    protected $type = 'string';

    /**
     * 文本的最大长度
     *
     * @var int
     */
    protected $length = 255;

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
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @param int $length
     */
    public function setLength(int $length): void
    {
        $this->length = $length;
    }

}