<?php


namespace Teebb\CoreBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * 格式化器Entity
 *
 * @ORM\Entity
 * @Assert\EnableAutoMapping
 */
class Formatter
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", unique=true)
     * @Gedmo\Translatable
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(type="string", unique=true)
     */
    private $alias;

    /**
     * 当前格式化器所使用的 ckeditor 配置
     * @var string|null
     * @ORM\Column(type="string")
     */
    private $ckEditorConfig;

    /**
     * 格式化器的过滤设置
     * @var array|null
     * @ORM\Column(type="array")
     */
    private $filterSettings;

    /**
     * 格式化器的过滤设置
     *
     * @ORM\ManyToMany(targetEntity="Teebb\CoreBundle\Entity\Group")
     * @ORM\JoinTable(name="formatters_groups",
     *      joinColumns={@ORM\JoinColumn(name="formatter_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    private $groups;

    /**
     * @Gedmo\Locale
     * @var string
     */
    private $locale;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
    public function getAlias(): ?string
    {
        return $this->alias;
    }

    /**
     * @param string|null $alias
     */
    public function setAlias(?string $alias): void
    {
        $this->alias = $alias;
    }

    /**
     * @return string|null
     */
    public function getCkEditorConfig(): ?string
    {
        return $this->ckEditorConfig;
    }

    /**
     * @param string|null $ckEditorConfig
     */
    public function setCkEditorConfig(?string $ckEditorConfig): void
    {
        $this->ckEditorConfig = $ckEditorConfig;
    }

    /**
     * @return array|null
     */
    public function getFilterSettings(): ?array
    {
        return $this->filterSettings;
    }

    /**
     * @param array|null $filterSettings
     */
    public function setFilterSettings(?array $filterSettings): void
    {
        $this->filterSettings = $filterSettings;
    }

    /**
     * @param $locale
     */
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * 用于持久化格式化器类型在字段表中的存储
     * @return string|null
     */
    public function __toString()
    {
        return $this->alias;
    }

    /**
     * @return Collection|Group[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->contains($group)) {
            $this->groups->removeElement($group);
        }

        return $this;
    }

}