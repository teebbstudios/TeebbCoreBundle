<?php


namespace Teebb\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

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
     * 内容发布状态 draft publish
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    protected $status;

    /**
     * 内容别名用于URL
     * @var string|null
     * @ORM\Column(type="string", length=255, unique=true)
     * @Gedmo\Slug(fields={"title"},unique=true)
     */
    protected $slug;

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

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     */
    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string|null $slug
     */
    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

}