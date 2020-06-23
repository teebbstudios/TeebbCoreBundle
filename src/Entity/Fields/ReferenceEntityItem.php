<?php


namespace Teebb\CoreBundle\Entity\Fields;

use Doctrine\ORM\Mapping as ORM;

/**
 * 引用内容，taxonomy，用:户字段在库中的存储
 *
 * @ORM\Entity(repositoryClass="Teebb\CoreBundle\Repository\Fields\FieldRepository")
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class ReferenceEntityItem extends BaseFieldItem
{
    /**
     * 多对一关系 对应Entity的entity_id 动态映射
     *
     * @var integer
     */
    private $value;

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * Todo: 参数类型有错，应修改为具体entity
     * @param int $value
     */
    public function setValue(int $value): void
    {
        $this->value = $value;
    }

}