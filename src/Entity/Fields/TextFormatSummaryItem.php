<?php


namespace Teebb\CoreBundle\Entity\Fields;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * 长文本已格式化带摘要类型
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
     * @ORM\Column(type="text", nullable=true, name="text_body_value")
     */
    private $value;

    /**
     * 摘要
     * @var string|null
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
    public function getSummary(): ?string
    {
        return $this->summary;
    }

    /**
     * @param string|null $summary
     */
    public function setSummary(?string $summary): void
    {
        $this->summary = $summary;
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