<?php


namespace Teebb\CoreBundle\Event;


use Teebb\CoreBundle\Entity\BaseContent;

class AfterContentPersistedEvent
{
    private $content;

    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * @return BaseContent
     */
    public function getContent(): BaseContent
    {
        return $this->content;
    }
}