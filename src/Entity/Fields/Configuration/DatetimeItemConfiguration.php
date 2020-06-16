<?php


namespace Teebb\CoreBundle\Entity\Fields\Configuration;

/**
 * 日期类型字段的设置在数据库中的存储不包含时区信息
 */
class DatetimeItemConfiguration extends BaseItemConfiguration
{
    /**
     * doctrine type 用于修改值存储字段在数据库中的类型
     *
     * @var string
     */
    protected $type = 'datetime';

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

}