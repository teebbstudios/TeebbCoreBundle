<?php


namespace Teebb\CoreBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Taxonomy类型内容
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
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
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Teebb\CoreBundle\Entity\Taxonomy", inversedBy="children")
     * @ORM\JoinColumn(name="parent_taxonomy_id", nullable=true, onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Teebb\CoreBundle\Entity\Taxonomy", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

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
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity="Teebb\CoreBundle\Entity\Taxonomy")
     * @ORM\JoinColumn(name="tree_root", referencedColumnName="id", onDelete="CASCADE")
     */
    private $root;

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

    /**
     * @return mixed
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * @param mixed $lft
     */
    public function setLft($lft): void
    {
        $this->lft = $lft;
    }

    /**
     * @return mixed
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * @param mixed $lvl
     */
    public function setLvl($lvl): void
    {
        $this->lvl = $lvl;
    }

    /**
     * @return mixed
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * @param mixed $rgt
     */
    public function setRgt($rgt): void
    {
        $this->rgt = $rgt;
    }

    /**
     * @return mixed
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param mixed $root
     */
    public function setRoot($root): void
    {
        $this->root = $root;
    }

    /**
     * @return Taxonomy[]|null
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param Taxonomy[]|null $children
     */
    public function setChildren($children): void
    {
        $this->children = $children;
    }

}