<?php
/**
 * This file is part of the TeebbCoreBundle package.
 *
 * Author: Quan Weiwei <qww.zone@gmail.com>
 * Date: 2020/5/21
 * (c) teebbstudios
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Teebb\CoreBundle\Entity\Types;


use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * 类型Entity. 删除类型删除所有字段和所有字段表
 *
 * @ORM\Entity(repositoryClass="Teebb\CoreBundle\Repository\Types\EntityTypeRepository")
 * @Assert\EnableAutoMapping
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class Types implements TypeInterface, Translatable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * 类型bundle, 比如："types", "taxonomy"，"comment"
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $bundle;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     */
    protected $label;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     * @Assert\Regex("/^(?!_)(?!.*?_$)[a-zA-Z0-9_]+$/")
     */
    protected $typeAlias;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @Gedmo\Locale
     * @var string
     */
    private $locale;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getBundle(): ?string
    {
        return $this->bundle;
    }

    /**
     * @param string $bundle
     */
    public function setBundle(string $bundle): void
    {
        $this->bundle = $bundle;
    }

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * @return string|null
     */
    public function getTypeAlias(): ?string
    {
        return $this->typeAlias;
    }

    /**
     * @param string $typeAlias
     */
    public function setTypeAlias(string $typeAlias): void
    {
        $this->typeAlias = $typeAlias;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param $locale
     */
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

}