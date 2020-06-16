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
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

}