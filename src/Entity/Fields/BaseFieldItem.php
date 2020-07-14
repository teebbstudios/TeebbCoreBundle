<?php


namespace Teebb\CoreBundle\Entity\Fields;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Field Entity基类
 *
 * @ORM\MappedSuperclass()
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class BaseFieldItem
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * 内容实体类型的别名 比如：'content', 'taxonomy', 'comment'等
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $types;

    /**
     * 具体内容entity， many-to-one, 多个字段值对应一个内容entity
     * @var object|null
     */
    protected $entity;

    /**
     * 同一字段不限制数量时，用于排序
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $delta;

    /**
     * @var string
     * @Gedmo\Locale
     */
    protected $locale;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getTypes(): ?string
    {
        return $this->types;
    }

    /**
     * @param string|null $types
     */
    public function setTypes(?string $types): void
    {
        $this->types = $types;
    }

    /**
     * @return object|null
     */
    public function getEntity(): ?object
    {
        return $this->entity;
    }

    /**
     * @param object|null $entity
     */
    public function setEntity(?object $entity): void
    {
        $this->entity = $entity;
    }

    /**
     * @return int
     */
    public function getDelta(): int
    {
        return $this->delta;
    }

    /**
     * @param int $delta
     */
    public function setDelta(int $delta): void
    {
        $this->delta = $delta;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
}