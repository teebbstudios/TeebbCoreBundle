<?php


namespace Teebb\CoreBundle\Entity\Fields\Configuration;

/**
 * 链接类型字段的设置
 */
class LinkItemConfiguration extends BaseItemConfiguration
{
    /**
     * doctrine type 用于修改值存储字段在数据库中的类型
     *
     * @var string
     */
    protected $type = 'string';

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

}