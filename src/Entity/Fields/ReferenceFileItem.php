<?php


namespace Teebb\CoreBundle\Entity\Fields;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

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
     * @todo 需要修改为文件类型entity
     * @var integer
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
     * @return int
     */
    public function getTargetFile(): int
    {
        return $this->targetFile;
    }

    /**
     * @param int $targetFile
     */
    public function setTargetFile(int $targetFile): void
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