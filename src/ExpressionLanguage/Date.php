<?php


namespace Teebb\CoreBundle\ExpressionLanguage;


class Date
{
    public $Year;

    public $month;

    public $day;

    public function __construct()
    {
        $datetime = new \DateTime();
        $this->Year = $datetime->format('Y');
        $this->month = $datetime->format('m');
        $this->day = $datetime->format('d');
    }
}