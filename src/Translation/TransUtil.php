<?php


namespace Teebb\CoreBundle\Translation;


use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Teebb\CoreBundle\Annotation\Translation;

class TransUtil
{
    /**
     * @var object
     */
    private $annotation;

    public function __construct(object $annotation)
    {
        $this->annotation = $annotation;
    }

    public function trans(Translator $translator): string
    {
        return $this->annotation instanceof Translation ?
            $translator->trans($this->annotation->message, $this->annotation->arguments, $this->annotation->domain) : $this->annotation;
    }
}