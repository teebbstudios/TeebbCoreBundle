<?php


namespace Teebb\CoreBundle\Entity\Fields;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Teebb\CoreBundle\Repository\Fields\FieldRepository")
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class ListIntegerItem extends BaseFieldItem
{
    /**
     * @var array|null
     */
    private $value;

    /**
     * @return array|null
     */
    public function getValue(): ?array
    {
        return $this->value;
    }

    /**
     * @param array|null $value
     */
    public function setValue(?array $value): void
    {
        $this->value = $value;
    }
}