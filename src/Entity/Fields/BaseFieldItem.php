<?php


namespace Teebb\CoreBundle\Entity\Fields;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * Field Entity基类
 *
 * @ORM\MappedSuperclass()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class BaseFieldItem
{
    use SoftDeleteableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * 内容实体类型的别名 比如：'content', 'taxonomy', 'comment'等
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $types;

    /**
     * 具体内容entity， many-to-one, 多个字段值对应一个内容entity
     * @var integer
     *
     */
    private $entity;

    /**
     * 同一字段不限制数量时，用于排序
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $delta;

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
     * @return string
     */
    public function getTypes(): string
    {
        return $this->types;
    }

    /**
     * @param string $types
     */
    public function setTypes(string $types): void
    {
        $this->types = $types;
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

}