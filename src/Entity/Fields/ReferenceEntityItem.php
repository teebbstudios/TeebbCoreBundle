<?php


namespace Teebb\CoreBundle\Entity\Fields;

use Doctrine\ORM\Mapping as ORM;

/**
 * 引用内容，taxonomy，用:户字段在库中的存储
 *
 * @ORM\Entity(repositoryClass="Teebb\CoreBundle\Repository\Fields\FieldRepository")
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class ReferenceEntityItem extends BaseFieldItem
{
    /**
     * 多对一关系 对应文件库的entity_id
     * @todo 需要修改为文件类型entity
     * @var integer
     */
    private $targetEntity;

    /**
     * @return int
     */
    public function getTargetEntity(): int
    {
        return $this->targetEntity;
    }

    /**
     * @param int $targetEntity
     */
    public function setTargetEntity(int $targetEntity): void
    {
        $this->targetEntity = $targetEntity;
    }

}