<?php


namespace Teebb\CoreBundle\Entity\Fields;

use Doctrine\ORM\Mapping as ORM;

/**
 * 在数据库里仅用一个column存储字段的值
 *
 * @ORM\Entity
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class SimpleValueItem extends BaseFieldItem
{
    private $value;
}