<?php


namespace Teebb\CoreBundle\Entity\Fields;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * 文本已格式化类型
 *
 * @ORM\Entity(repositoryClass="Teebb\CoreBundle\Repository\Fields\FieldRepository")
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class StringFormatItem extends BaseFieldItem
{
    /**
     * 需要动态映射 type= string
     * @var string|null
     */
    private $value;

    /**
     * 格式化器的名称
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $formatter;

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string|null $value
     */
    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return string|null
     */
    public function getFormatter(): ?string
    {
        return $this->formatter;
    }

    /**
     * @param string|null $formatter
     */
    public function setFormatter(?string $formatter): void
    {
        $this->formatter = $formatter;
    }

}