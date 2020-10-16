<?php


namespace Teebb\CoreBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Menu菜单类
 * @ORM\Entity
 * @Assert\EnableAutoMapping
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class Menu
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(type="string", unique=true, length=255)
     */
    private $menuAlias;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @return integer|null
     */
    public function getId(): ?int
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
    public function getMenuAlias(): ?string
    {
        return $this->menuAlias;
    }

    /**
     * @param string|null $menuAlias
     */
    public function setMenuAlias(?string $menuAlias): void
    {
        $this->menuAlias = $menuAlias;
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

}