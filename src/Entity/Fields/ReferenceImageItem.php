<?php


namespace Teebb\CoreBundle\Entity\Fields;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * 引用图像字段在库中的存储
 *
 * @ORM\Entity(repositoryClass="Teebb\CoreBundle\Repository\Fields\FieldRepository")
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class ReferenceImageItem extends BaseFieldItem
{
    /**
     * 多对一关系 对应文件库的entity_id
     * @todo 需要修改为图像实体entity
     * @var integer
     */
    private $targetImageFile;

    /**
     * 图像alt信息
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $alt;

    /**
     * 图像title信息
     * @var string
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
}