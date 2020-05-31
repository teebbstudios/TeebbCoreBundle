<?php


namespace Teebb\CoreBundle\Translation;


use Symfony\Contracts\Translation\TranslatorInterface;
use Teebb\CoreBundle\Annotation\Translation;

class AnnotationTranslation
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function trans($annotation): string
    {
        return $annotation instanceof Translation ?
            $this->translator->trans($annotation->message, $annotation->arguments, $annotation->domain) : $annotation;
    }
}