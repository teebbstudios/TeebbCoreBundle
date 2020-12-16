<?php


namespace Teebb\CoreBundle\Event;


class OptionCacheEvent
{
    //生成设置缓存
    public const GET_OPTION_CACHE = 'get.option.cache';

    //删除设置缓存
    public const DELETE_OPTION_CACHE = 'delete.option.cache';

    private $optionName;

    /**
     * @return mixed
     */
    public function getOptionName()
    {
        return $this->optionName;
    }

    /**
     * @param mixed $optionName
     */
    public function setOptionName($optionName): void
    {
        $this->optionName = $optionName;
    }


}