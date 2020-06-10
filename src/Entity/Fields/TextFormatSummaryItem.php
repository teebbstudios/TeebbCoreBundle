<?php


namespace Teebb\CoreBundle\Entity\Fields;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * 文本已格式化类型、长文本已格式化类型
 *
 * @ORM\Entity(repositoryClass="Teebb\CoreBundle\Repository\Fields\FieldRepository")
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class TextFormatSummaryItem extends BaseFieldItem
{
    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(type="text")
     */
    private $value;

    /**
     * 摘要
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     */
    private $summary;

    /**
     * 格式化器的名称
     * @var string
     * @ORM\Column(type="string")
     */
    private $formatter;
}