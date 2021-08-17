<?php


namespace Teebb\CoreBundle\Event;


use Teebb\CoreBundle\Entity\BaseContent;
use Teebb\CoreBundle\Entity\Fields\BaseFieldItem;

class AfterFieldItemPersistedEvent
{
    private $fieldItem;

    private $subject;

    public function __construct($subject, $fieldItem)
    {
        $this->subject = $subject;
        $this->fieldItem = $fieldItem;
    }

    /**
     * @return BaseFieldItem
     */
    public function getFieldItem()
    {
        return $this->fieldItem;
    }

    /**
     * @return BaseContent
     */
    public function getSubject()
    {
        return $this->subject;
    }

}