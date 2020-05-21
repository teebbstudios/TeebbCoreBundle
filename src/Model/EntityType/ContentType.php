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

namespace Teebb\CoreBundle\Model\EntityType;

use Gedmo\Timestampable\Traits\TimestampableEntity;
use Teebb\CoreBundle\Annotation\EntityType;
use Teebb\CoreBundle\Annotation\Translation;
use Doctrine\ORM\Mapping as ORM;

/**
 * 内容类型.
 *
 * @EntityType(
 *     description=@Translation(message="Content Type", domain="TeebbCoreBundle")
 * )
 *
 * @ORM\Entity
 * @ORM\Table(name="teebb_content_type")
 * @ORM\MappedSuperclass()
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class ContentType extends AbstractEntityType
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $alias;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     */
    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getDescription(): string
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