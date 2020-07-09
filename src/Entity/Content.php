<?php


namespace Teebb\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Types类型内容
 *
 * @ORM\Entity(repositoryClass="Teebb\CoreBundle\Repository\BaseContentRepository")
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class Content extends BaseContent
{
    /**
     * 内容标题
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * 内容类型别名
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    protected $type;

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }
}