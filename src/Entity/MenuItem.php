<?php


namespace Teebb\CoreBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * MenuItem 菜单项
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class MenuItem
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("main")
     */
    private $id;

    /**
     * 当前菜单项属于哪个菜单 仅用于根菜单项
     * @var Menu|null
     * @ORM\ManyToOne(targetEntity="Teebb\CoreBundle\Entity\Menu")
     * @ORM\JoinColumn(name="menu_id", nullable=true, referencedColumnName="id")
     */
    private $menu;

    /**
     * 菜单链接
     * @var string|null
     * @ORM\Column(type="string", nullable=true, length=255)
     * @Groups("main")
     */
    private $menuLink;

    /**
     * 菜单标题
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     * @Groups("main")
     */
    private $menuTitle;

    /**
     * 菜单链接属性
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     * @Groups("main")
     */
    private $menuTitleAttr;

    /**
     * @var MenuItem|null
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Teebb\CoreBundle\Entity\MenuItem", inversedBy="children")
     * @ORM\JoinColumn(name="parent_menu_item_id", nullable=true, onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Teebb\CoreBundle\Entity\MenuItem", mappedBy="parent")
     * @ORM\OrderBy({"priority" = "ASC", "lft" = "ASC"})
     */
    private $children;

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
     * @ORM\ManyToOne(targetEntity="Teebb\CoreBundle\Entity\MenuItem")
     * @ORM\JoinColumn(name="tree_root", referencedColumnName="id", onDelete="CASCADE")
     */
    private $root;

    /**
     * 用于菜单项排序
     * @var int|null
     * @ORM\Column(name="priority", type="integer", options={"default": 0})
     */
    private $priority = 0;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Menu|null
     */
    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    /**
     * @param Menu|null $menu
     */
    public function setMenu(?Menu $menu): void
    {
        $this->menu = $menu;
    }

    /**
     * @return string|null
     */
    public function getMenuLink(): ?string
    {
        return $this->menuLink;
    }

    /**
     * @param string|null $menuLink
     */
    public function setMenuLink(?string $menuLink): void
    {
        $this->menuLink = $menuLink;
    }

    /**
     * @return string|null
     */
    public function getMenuTitle(): ?string
    {
        return $this->menuTitle;
    }

    /**
     * @param string|null $menuTitle
     */
    public function setMenuTitle(?string $menuTitle): void
    {
        $this->menuTitle = $menuTitle;
    }

    /**
     * @return string|null
     */
    public function getMenuTitleAttr(): ?string
    {
        return $this->menuTitleAttr;
    }

    /**
     * @param string|null $menuTitleAttr
     */
    public function setMenuTitleAttr(?string $menuTitleAttr): void
    {
        $this->menuTitleAttr = $menuTitleAttr;
    }

    /**
     * @return MenuItem|null
     */
    public function getParent(): ?MenuItem
    {
        return $this->parent;
    }

    /**
     * @param MenuItem|null $parent
     */
    public function setParent(?MenuItem $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     */
    public function setChildren($children): void
    {
        $this->children = $children;
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
     * @return int|null
     */
    public function getPriority(): ?int
    {
        return $this->priority;
    }

    /**
     * @param int|null $priority
     */
    public function setPriority(?int $priority): void
    {
        $this->priority = $priority;
    }

    public function hasChildren(): bool
    {
        return !$this->children->isEmpty();
    }
}