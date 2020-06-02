<?php


namespace Teebb\CoreBundle\Entity\Fields;

use Doctrine\ORM\Mapping as ORM;
/**
 * 用于存储字段值的entity，不同的字段可以使用相同的entity
 *
 * @ORM\Entity
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class IntegerItem extends BaseFieldItem
{
    /**
     * 不同类型字段的值存储为不同的数据库类型
     *
     */
    private $value;
}