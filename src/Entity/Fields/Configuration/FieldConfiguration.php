<?php


namespace Teebb\CoreBundle\Entity\Fields;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Teebb\CoreBundle\Configuration\FieldItemDepartConfigurationInterface;

/**
 * 用于存储字段的设置信息
 * @todo 需要添加自定义Repository类继承自SortableRepository
 *
 * @ORM\Entity
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class FieldConfiguration
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * 字段所属的内容实体类型注释中的别名
     *
     * @Gedmo\SortableGroup
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $type;

    /**
     * 内容实体类型Entity的别名
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $alias;

    /**
     * @var integer
     *
     * @Gedmo\SortablePosition
     * @ORM\Column(type="integer")
     */
    private $delta;

    /**
     * 字段entity的别名
     *
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     */
    private $fieldAlias;

    /**
     * 分离不同类型的设置
     *
     * @var FieldItemDepartConfigurationInterface
     *
     * @ORM\Column(type="depart")
     */
    private $settings;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
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
    public function getFieldAlias(): string
    {
        return $this->fieldAlias;
    }

    /**
     * @param string $fieldAlias
     */
    public function setField(string $fieldAlias): void
    {
        $this->fieldAlias = $fieldAlias;
    }

    /**
     * @return FieldItemDepartConfigurationInterface
     */
    public function getSettings(): FieldItemDepartConfigurationInterface
    {
        return $this->settings;
    }

    /**
     * @param FieldItemDepartConfigurationInterface $settings
     */
    public function setSettings(FieldItemDepartConfigurationInterface $settings): void
    {
        $this->settings = $settings;
    }

}