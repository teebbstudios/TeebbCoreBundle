<?php


namespace Teebb\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Comment类型内容
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
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
    private $commentType;

    /**
     * 评论状态 1 已提交  2 审核中  3 审核通过  4 驳回
     * @var int|null
     * @ORM\Column(type="smallint", options={"default":1})
     */
    private $commentStatus;

//    /**
//     * Todo: 评论作者
//     * @var
//     */
//    private $author;

    /**
     * 如果是匿名用户则需要添加姓名
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * 如果是匿名用户则需要添加邮箱
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * 如果是匿名用户则需要添加主页
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $homePage;

    /**
     * 评论的主题
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private $subject;

    /**
     * 所评论的target内容对象的bundle
     *
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private $bundle;

    /**
     * 所评论的内容对象的内容类型Entity别名
     *
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private $typeAlias;

    /**
     * 当前评论的thread_id
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private $thread;

    /**
     * 当前评论表单对应的字段类型的别名，例如article类型内容有评论字段：comment_1, comment_2, 此属性用于区分评论字段
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private $commentFieldAlias;

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
     * @ORM\ManyToOne(targetEntity="Teebb\CoreBundle\Entity\Comment")
     * @ORM\JoinColumn(name="tree_root", referencedColumnName="id", onDelete="CASCADE")
     */
    private $root;

    /**
     * @var Comment|null
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Teebb\CoreBundle\Entity\Comment", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", nullable=true, referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Teebb\CoreBundle\Entity\Comment", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

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

    /**
     * @return int|null
     */
    public function getCommentStatus(): ?int
    {
        return $this->commentStatus;
    }

    /**
     * @param int|null $commentStatus
     */
    public function setCommentStatus(?int $commentStatus): void
    {
        $this->commentStatus = $commentStatus;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getHomePage(): ?string
    {
        return $this->homePage;
    }

    /**
     * @param string|null $homePage
     */
    public function setHomePage(?string $homePage): void
    {
        $this->homePage = $homePage;
    }

    /**
     * @return string|null
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @param string|null $subject
     */
    public function setSubject(?string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return string|null
     */
    public function getBundle(): ?string
    {
        return $this->bundle;
    }

    /**
     * @param string|null $bundle
     */
    public function setBundle(?string $bundle): void
    {
        $this->bundle = $bundle;
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
    public function getThread(): ?string
    {
        return $this->thread;
    }

    /**
     * @param string|null $thread
     */
    public function setThread(?string $thread): void
    {
        $this->thread = $thread;
    }

    /**
     * @return string|null
     */
    public function getCommentFieldAlias(): ?string
    {
        return $this->commentFieldAlias;
    }

    /**
     * @param string|null $commentFieldAlias
     */
    public function setCommentFieldAlias(?string $commentFieldAlias): void
    {
        $this->commentFieldAlias = $commentFieldAlias;
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
     * @return Comment|null
     */
    public function getParent(): ?Comment
    {
        return $this->parent;
    }

    /**
     * @param Comment|null $parent
     */
    public function setParent(?Comment $parent): void
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

}