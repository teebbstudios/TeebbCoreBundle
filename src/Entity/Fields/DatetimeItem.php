<?php


namespace Teebb\CoreBundle\Entity\Fields;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Teebb\CoreBundle\Repository\Fields\FieldRepository")
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class DatetimeItem extends BaseFieldItem
{
    /**
     * 动态映射
     * @var \DateTime|null
     */
    private $value;

    /**
     * @return \DateTime|null
     */
    public function getValue(): ?\DateTime
    {
        return $this->value;
    }

    /**
     * @param \DateTime|null $value
     */
    public function setValue(?\DateTime $value): void
    {
        $this->value = $value;
    }
}