<?php


namespace Teebb\CoreBundle\Entity\Fields\Configuration;


use Doctrine\Common\Collections\ArrayCollection;
use Teebb\CoreBundle\Entity\Taxonomy;
use Teebb\CoreBundle\Entity\Types\Types;

class ReferenceTaxonomyItemConfiguration extends BaseItemConfiguration
{
    /**
     * 如果类型为 entity 则不处理doctrine metadata
     *
     * @var string
     */
    protected $type = 'entity';

    /**
     * 要引用的taxonomy类型别名数组
     * @var ArrayCollection
     */
    protected $referenceTypes;

    /**
     * 如果标签不存在则添加标签到某个Taxonomy类型
     * @var Types
     */
    protected $autoCreateToType;

    protected $referenceTargetEntity = Taxonomy::class;

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
     * @return Types|null
     */
    public function getAutoCreateToType(): ?Types
    {
        return $this->autoCreateToType;
    }

    /**
     * @param Types $autoCreateToType
     */
    public function setAutoCreateToType(Types $autoCreateToType): void
    {
        $this->autoCreateToType = $autoCreateToType;
    }

    /**
     * @return string
     */
    public function getReferenceTargetEntity(): string
    {
        return $this->referenceTargetEntity;
    }

}