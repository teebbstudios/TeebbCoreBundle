<?php


namespace Teebb\CoreBundle\Entity\Fields;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Teebb\CoreBundle\Entity\FileManaged;

/**
 * 引用文件字段在库中的存储
 *
 * @ORM\Entity(repositoryClass="Teebb\CoreBundle\Repository\Fields\FieldRepository")
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class ReferenceFileItem extends BaseFieldItem
{
    /**
     * 多对一关系 对应文件库的entity_id
     * @var FileManaged|null
     * @ORM\ManyToOne(targetEntity="Teebb\CoreBundle\Entity\FileManaged")
     * @ORM\JoinColumn(name="reference_file_id")
     */
    private $value;

    /**
     * 是否显示此文件
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $showFile = true;

    /**
     * 文件描述
     * @var string|null
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

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
     * @return bool
     */
    public function isShowFile(): bool
    {
        return $this->showFile;
    }

    /**
     * @param bool $showFile
     */
    public function setShowFile(bool $showFile): void
    {
        $this->showFile = $showFile;
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