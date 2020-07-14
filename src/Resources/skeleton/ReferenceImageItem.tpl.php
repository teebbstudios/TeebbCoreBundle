<?= "<?php\n" ?>


namespace <?= $namespace ?>;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Teebb\CoreBundle\Entity\FileManaged;

/**
 * 引用图像字段在库中的存储
 *
 * @ORM\Entity(repositoryClass="Teebb\CoreBundle\Repository\Fields\FieldRepository")
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class <?= $class_name ?> extends BaseFieldItem
{
    /**
     * 单向多对一关系 对应文件库的entity_id
     * @var FileManaged|null
     * @ORM\ManyToOne(targetEntity="Teebb\CoreBundle\Entity\FileManaged")
     * @ORM\JoinColumn(name="reference_file_id")
     */
    private $value;

    /**
     * 图像alt信息
     * @var string|null
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $alt;

    /**
     * 图像title信息
     * @var string|null
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * 图像宽度信息
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $width;

    /**
     * 图像高度信息
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $height;

    /**
     * @return FileManaged|null
     */
    public function getValue(): ?FileManaged
    {
        return $this->value;
    }

    /**
     * @param FileManaged|null $value
     */
    public function setValue(?FileManaged $value): void
    {
        $this->value = $value;
    }

    /**
     * @return string|null
     */
    public function getAlt(): ?string
    {
        return $this->alt;
    }

    /**
     * @param string|null $alt
     */
    public function setAlt(?string $alt): void
    {
        $this->alt = $alt;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return int|null
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }

    /**
     * @param int|null $width
     */
    public function setWidth(?int $width): void
    {
        $this->width = $width;
    }

    /**
     * @return int|null
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * @param int|null $height
     */
    public function setHeight(?int $height): void
    {
        $this->height = $height;
    }

}