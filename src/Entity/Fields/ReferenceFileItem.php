<?php


namespace Teebb\CoreBundle\Entity\Fields;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Teebb\CoreBundle\Entity\File;

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
     * @var File
     * @ORM\ManyToOne(targetEntity="Teebb\CoreBundle\Entity\File")
     */
    private $targetFile;

    /**
     * 是否显示此文件
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $showFile;

    /**
     * 文件描述
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @return File
     */
    public function getTargetFile(): File
    {
        return $this->targetFile;
    }

    /**
     * @param File $targetFile
     */
    public function setTargetFile(File $targetFile): void
    {
        $this->targetFile = $targetFile;
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

}