<?php


namespace Teebb\CoreBundle\Entity\TextFormat;


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

//    /**
//     * Todo: 用户组
//     * @var array|null
//     * @ORM\Column(type="string", unique=true)
//     */
//    private $roles = [];

    /**
     * 格式化器的过滤设置
     * @var array|null
     * @ORM\Column(type="array")
     */
    private $filterSettings;

    /**
     * @Gedmo\Locale
     * @var string
     */
    private $locale;

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
//
//    /**
//     * @return array|null
//     */
//    public function getRoles(): ?array
//    {
//        return $this->roles;
//    }
//
//    /**
//     * @param array|null $roles
//     */
//    public function setRoles(?array $roles): void
//    {
//        $this->roles = $roles;
//    }

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
}