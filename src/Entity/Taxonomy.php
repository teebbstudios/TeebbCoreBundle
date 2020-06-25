<?php


namespace Teebb\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Taxonomy类型内容
 *
 * @ORM\Entity(repositoryClass="Teebb\CoreBundle\Repository\BaseContentRepository")
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class Taxonomy extends BaseContent
{
    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    protected $taxonomyType;

    /**
     * @return string|null
     */
    public function getTaxonomyType(): ?string
    {
        return $this->taxonomyType;
    }

    /**
     * @param string|null $taxonomyType
     */
    public function setTaxonomyType(?string $taxonomyType): void
    {
        $this->taxonomyType = $taxonomyType;
    }
}