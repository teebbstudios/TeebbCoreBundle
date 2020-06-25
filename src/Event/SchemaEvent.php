<?php


namespace Teebb\CoreBundle\Event;


use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * 用于创建删除字段表的事件
 */
class SchemaEvent extends GenericEvent
{
    public const CREATE_SCHEMA = 'create.schema';
    public const DROP_SCHEMA = 'drop.schema';

    /**
     * 内容实体Entity全类名
     * @var string
     */
    private $contentEntity;

    /**
     * @return string
     */
    public function getContentEntity()
    {
        return $this->contentEntity;
    }

    /**
     * @param string $contentEntity
     */
    public function setContentEntity(string $contentEntity): void
    {
        $this->contentEntity = $contentEntity;
    }

}