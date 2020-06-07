<?php


namespace Teebb\CoreBundle\Twig\Extensions;


use Symfony\Contracts\Translation\TranslatorInterface;
use Teebb\CoreBundle\Translation\TranslatableMarkup;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TranslationMarkupExtension extends AbstractExtension
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('trans_markup', [$this, 'translationMarkup'])
        ];
    }

    public function translationMarkup(TranslatableMarkup $markup)
    {
        return $this->translator->trans($markup->getMessage(), $markup->getArguments(), $markup->getDomain());
    }
}