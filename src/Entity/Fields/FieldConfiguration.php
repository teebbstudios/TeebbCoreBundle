<?php


namespace Teebb\CoreBundle\Entity\Fields;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Teebb\CoreBundle\Entity\Fields\Configuration\FieldItemDepartConfigurationInterface;

/**
 * 用于存储字段的设置信息
 *
 * @ORM\Entity(repositoryClass="Teebb\CoreBundle\Repository\Fields\FieldConfigurationRepository")
 * @Assert\EnableAutoMapping
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
     * 字段所属的内容实体类型注释中的别名。"comment","types","taxonomy"等
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $type;

    /**
     * 内容实体类型Entity的别名。比如文章"article"，"page"
     *
     * @Gedmo\SortableGroup
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $typeAlias;

    /**
     * 字段entity的标题
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $fieldLabel;

    /**
     * 字段entity的别名
     *
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     * @Assert\Regex("/^(?!_)(?!.*?_$)[a-zA-Z0-9_]+$/")
     */
    private $fieldAlias;

    /**
     * 字段的类型，从字段列表传过来的字段值
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $fieldType;

    /**
     * @var integer
     *
     * @Gedmo\SortablePosition
     * @ORM\Column(type="integer")
     */
    private $delta;

    /**
     * 分离不同类型的设置
     *
     * @var FieldItemDepartConfigurationInterface
     *
     * @ORM\Column(type="array", nullable=true)
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
     * @return string|null
     */
    public function getType(): ?string
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
    public function getFieldLabel(): ?string
    {
        return $this->fieldLabel;
    }

    /**
     * @param mixed $fieldLabel
     */
    public function setFieldLabel($fieldLabel): void
    {
        $this->fieldLabel = $fieldLabel;
    }

    /**
     * @return string|null
     */
    public function getFieldAlias(): ?string
    {
        return $this->fieldAlias;
    }

    /**
     * @param mixed $fieldAlias
     */
    public function setFieldAlias($fieldAlias): void
    {
        $this->fieldAlias = $fieldAlias;
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
     * @return string|null
     */
    public function getFieldType(): ?string
    {
        return $this->fieldType;
    }

    /**
     * @param string $fieldType
     */
    public function setFieldType(string $fieldType): void
    {
        $this->fieldType = $fieldType;
    }

    /**
     * @return FieldItemDepartConfigurationInterface|null
     */
    public function getSettings(): ?FieldItemDepartConfigurationInterface
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