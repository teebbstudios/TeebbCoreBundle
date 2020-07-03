<?php


namespace Teebb\CoreBundle\Entity\Fields\Configuration;


use Doctrine\Common\Collections\ArrayCollection;
use Teebb\CoreBundle\Entity\Content;
use Teebb\CoreBundle\Entity\Types\Types;

class ReferenceContentItemConfiguration extends BaseItemConfiguration
{
    /**
     * 如果类型为 entity 则不处理doctrine metadata
     *
     * @var string
     */
    protected $type = 'entity';

    /**
     * 要引用的内容类型别名typeAlias数组
     * @var ArrayCollection
     */
    protected $referenceTypes;

    protected $referenceTargetEntity = Content::class;

    public function __construct()
    {
        $this->referenceTypes = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return ArrayCollection|null
     */
    public function getReferenceTypes(): ?ArrayCollection
    {
        return $this->referenceTypes;
    }

    /**
     * @param ArrayCollection $referenceTypes
     */
    public function setReferenceTypes(ArrayCollection $referenceTypes): void
    {
        $this->referenceTypes = $referenceTypes;
    }

    /**
     * @return string
     */
    public function getReferenceTargetEntity(): string
    {
        return $this->referenceTargetEntity;
    }

}