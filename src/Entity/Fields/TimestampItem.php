<?php


namespace Teebb\CoreBundle\Entity\Fields;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Teebb\CoreBundle\Repository\Fields\FieldRepository")
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class TimestampItem extends BaseFieldItem
{
    /**
     * 动态映射
     * @var integer|null
     */
    private $value;

    /**
     * @return int|null
     */
    public function getValue(): ?int
    {
        return $this->value;
    }

    /**
     * @param int|null $value
     */
    public function setValue(?int $value): void
    {
        $this->value = $value;
    }

}