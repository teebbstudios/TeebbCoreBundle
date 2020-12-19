<?php


namespace Teebb\CoreBundle\Entity\Fields\Configuration;




use Teebb\CoreBundle\Entity\User;

class ReferenceUserItemConfiguration extends BaseItemConfiguration
{
    /**
     * 如果类型为 entity 则不处理doctrine metadata
     *
     * @var string
     */
    protected $type = 'entity';

    /**
     * @var string
     */
    protected $referenceTargetEntity = User::class;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getReferenceTargetEntity(): string
    {
        return $this->referenceTargetEntity;
    }

}