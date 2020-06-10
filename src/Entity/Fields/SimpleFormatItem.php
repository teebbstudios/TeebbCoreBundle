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
class SimpleFormatItem extends BaseFieldItem
{
    /**
     * @Gedmo\Translatable
     */
    private $value;

    /**
     * 格式化器的名称
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $formatter;
}