<?php


namespace Teebb\CoreBundle\Entity\Fields;

/**
 * Field Entity基类
 *
 * @ORM\MappedSuperclass()
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class BaseFieldItem
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * 字段标题
     *
     * @var string
     */
    private $label;

    /**
     * 字段唯一别名
     *
     * @var string
     */
    private $alias;

}