<?php


namespace Teebb\CoreBundle\Configuration;

/**
 * 时间戳类型字段的设置，在数据库中的存储为整型
 */
class TimestampItemConfiguration extends BaseItemConfiguration
{
    /**
     * doctrine type 用于修改值存储字段在数据库中的类型
     *
     * @var string
     */
    protected $type = 'integer';
    
    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

}