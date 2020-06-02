<?php


namespace Teebb\CoreBundle\Entity\Fields;

use Doctrine\ORM\Mapping as ORM;
use Teebb\CoreBundle\Configuration\FieldItemDepartConfigurationInterface;

/**
 * 用于存储字段的设置信息
 *
 * @ORM\Entity
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class FieldSettings
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
     * @var string
     */
    private $type;

    /**
     * 内容实体类型Entity的别名
     *
     * @var string
     */
    private $alias;

    /**
     * 字段entity别名
     * @var string
     */
    private $field;

    /**
     * 分离不同类型的设置
     *
     * @var FieldItemDepartConfigurationInterface
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
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @param string $field
     */
    public function setField(string $field): void
    {
        $this->field = $field;
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