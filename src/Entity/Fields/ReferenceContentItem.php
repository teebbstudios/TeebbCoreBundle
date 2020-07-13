<?php


namespace Teebb\CoreBundle\Entity\Fields;

use Doctrine\ORM\Mapping as ORM;
use Teebb\CoreBundle\Entity\BaseContent;

/**
 * 引用内容字段在库中的存储
 *
 * @ORM\Entity(repositoryClass="Teebb\CoreBundle\Repository\Fields\FieldRepository")
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class ReferenceContentItem extends BaseFieldItem
{
    /**
     * 多对一关系 对应Entity的entity_id 动态映射
     *
     * @var BaseContent|null
     */
    private $value;

    /**
     * @return BaseContent|null
     */
    public function getValue(): ?BaseContent
    {
        return $this->value;
    }

    /**
     * @param BaseContent|null $value
     */
    public function setValue(?BaseContent $value): void
    {
        $this->value = $value;
    }

}