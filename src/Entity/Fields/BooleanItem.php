<?php


namespace Teebb\CoreBundle\Entity\Fields;

use Doctrine\ORM\Mapping as ORM;

/**
 * 用于存储字段值entity，不同的字段可以使用相同的entity存储结构
 *
 * @ORM\Entity
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class BooleanItem extends BaseFieldItem
{

}