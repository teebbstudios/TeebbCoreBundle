<?php


namespace Teebb\CoreBundle\Event;


class MenuCacheEvent
{
    //生成字段缓存
    public const GET_MENU_CACHE = 'get.menu.cache';

    //删除字段缓存
    public const DELETE_MENU_CACHE = 'delete.menu.cache';

    /**
     * @var string
     */
    private $menuName;

    /**
     * @return string
     */
    public function getMenuName(): string
    {
        return $this->menuName;
    }

    /**
     * @param string $menuName
     */
    public function setMenuName(string $menuName): void
    {
        $this->menuName = $menuName;
    }

}