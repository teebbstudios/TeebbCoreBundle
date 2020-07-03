<?php


namespace Teebb\CoreBundle\Entity\Fields\Configuration;




class ReferenceUserItemConfiguration extends BaseItemConfiguration
{
    /**
     * 如果类型为 entity 则不处理doctrine metadata
     *
     * @var string
     */
    protected $type = 'entity';

    /**
     * @todo  此处修改为user entity类
     * @var string
     */
    protected $referenceTargetEntity = '';

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