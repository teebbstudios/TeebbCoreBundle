<?php


namespace Teebb\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Comment类型内容
 *
 * @ORM\Entity(repositoryClass="Teebb\CoreBundle\Repository\BaseContentRepository")
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class Comment extends BaseContent
{
    /**
     * 评论类型别名
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    protected $commentType;

    /**
     * @return string|null
     */
    public function getCommentType(): ?string
    {
        return $this->commentType;
    }

    /**
     * @param string|null $commentType
     */
    public function setCommentType(?string $commentType): void
    {
        $this->commentType = $commentType;
    }

}