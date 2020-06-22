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
}