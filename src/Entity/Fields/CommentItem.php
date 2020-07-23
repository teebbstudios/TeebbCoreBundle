<?php


namespace Teebb\CoreBundle\Entity\Fields;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Teebb\CoreBundle\Repository\Fields\FieldRepository")
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class CommentItem extends BaseFieldItem
{
    /**
     * 当前内容的评论状态，0 关闭评论  1 正常评论
     * @var int|null
     * @ORM\Column(type="smallint", name="field_comment_status", options={"default":1})
     */
    private $value;

    /**
     * @return int|null
     */
    public function getValue(): ?int
    {
        return $this->value;
    }

    /**
     * @param int|null $value
     */
    public function setValue(?int $value): void
    {
        $this->value = $value;
    }
}