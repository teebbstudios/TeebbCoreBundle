<?php


namespace Teebb\CoreBundle\Entity\Fields\Configuration;

use Teebb\CoreBundle\Entity\Types\Types;

/**
 * 评论类型字段的设置
 */
class CommentItemConfiguration extends BaseItemConfiguration
{
    /**
     * @var string
     */
    protected $type = 'smallint';

    /**
     * 当前评论字段使用的评论类型
     *
     * @var Types|null
     */
    protected $commentType;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return Types|null
     */
    public function getCommentType(): ?Types
    {
        return $this->commentType;
    }

    /**
     * @param Types|null $commentType
     */
    public function setCommentType(?Types $commentType): void
    {
        $this->commentType = $commentType;
    }
}