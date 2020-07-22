<?php


namespace Teebb\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Taxonomy类型内容
 *
 * @ORM\Entity(repositoryClass="Teebb\CoreBundle\Repository\BaseContentRepository")
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class Taxonomy extends BaseContent
{
    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private $taxonomyType;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     * @Groups("main")
     */
    private $term;

    /**
     * 简短描述下分类词汇
     *
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     * @Groups("main")
     */
    private $description;

    /**
     * @var Taxonomy|null
     * @ORM\ManyToOne(targetEntity="Teebb\CoreBundle\Entity\Taxonomy")
     * @ORM\JoinColumn(name="parent_taxonomy_id", nullable=true)
     */
    private $parent;

    /**
     * 分类词汇slug
     *
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Slug(fields={"term"}, unique=true, updatable=false)
     * @Groups("main")
     */
    private $slug;

    /**
     * @return string|null
     */
    public function getTaxonomyType(): ?string
    {
        return $this->taxonomyType;
    }

    /**
     * @param string|null $taxonomyType
     */
    public function setTaxonomyType(?string $taxonomyType): void
    {
        $this->taxonomyType = $taxonomyType;
    }

    /**
     * @return string|null
     */
    public function getTerm(): ?string
    {
        return $this->term;
    }

    /**
     * @param string|null $term
     */
    public function setTerm(?string $term): void
    {
        $this->term = $term;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return Taxonomy|null
     */
    public function getParent(): ?Taxonomy
    {
        return $this->parent;
    }

    /**
     * @param Taxonomy|null $parent
     */
    public function setParent(?Taxonomy $parent): void
    {
        $this->parent = $parent;
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