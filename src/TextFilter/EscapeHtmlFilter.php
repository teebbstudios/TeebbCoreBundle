<?php


namespace Teebb\CoreBundle\TextFilter;

/**
 * 将任何HTML显示为纯文本
 */
class EscapeHtmlFilter implements TextFilterInterface
{
    /**
     * @var TextFilterInterface
     */
    private $nextFilter = null;

    public function addNextFilter(TextFilterInterface $textFilter): void
    {
        $this->nextFilter = $textFilter;
    }

    /**
     * 责任链模式，如果有下个过滤器则继续过滤
     * @param string $text
     * @return string
     */
    public function format(string $text): string
    {
        $result = htmlspecialchars($text);
        if (null !== $this->nextFilter) {
            $result = $this->nextFilter->format($result);
        }

        return $result;
    }

    public function hasExtra(): bool
    {
        return false;
    }

}