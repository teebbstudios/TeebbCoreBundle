<?php


namespace Teebb\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Teebb\CoreBundle\Entity\Types\Types;

/**
 * Content类型内容
 *
 * @ORM\Entity(repositoryClass="Teebb\CoreBundle\Repository\BaseContentRepository")
 * @ORM\EntityListeners({"Teebb\CoreBundle\Listener\ContentListener"})
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class Content extends BaseContent
{
    /**
     * 内容标题
     * @var string|null
     * @ORM\Column(type="string", length=255)
     * @Groups("main")
     */
    protected $title;

    /**
     * 内容类型别名
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    protected $typeAlias;

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
     * @Gedmo\Slug(fields={"title"}, unique=true, updatable=false)
     * @Groups("main")
     */
    protected $slug;

    /**
     * 内容作者
     * @var UserInterface
     * @ORM\ManyToOne(targetEntity="Teebb\CoreBundle\Entity\User")
     */
    protected $author;

    /**
     * 是否置顶
     * @var boolean
     * @ORM\Column(type="boolean", options={"default":false})
     */
    protected $boolTop = false;

    /**
     * @var Types|null
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
    public function getTypeAlias(): ?string
    {
        return $this->typeAlias;
    }

    /**
     * @param string|null $typeAlias
     */
    public function setTypeAlias(?string $typeAlias): void
    {
        $this->typeAlias = $typeAlias;
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

    /**
     * @return UserInterface|null
     */
    public function getAuthor(): ?UserInterface
    {
        return $this->author;
    }

    /**
     * @param UserInterface|null $author
     */
    public function setAuthor(?UserInterface $author): void
    {
        $this->author = $author;
    }

    /**
     * @return bool
     */
    public function isBoolTop(): bool
    {
        return $this->boolTop;
    }

    /**
     * @param bool $boolTop
     */
    public function setBoolTop(bool $boolTop): void
    {
        $this->boolTop = $boolTop;
    }

    /**
     * @return Types|null
     */
    public function getType(): ?Types
    {
        return $this->type;
    }

    /**
     * @param Types|null $type
     */
    public function setType(?Types $type): void
    {
        $this->type = $type;
    }

}