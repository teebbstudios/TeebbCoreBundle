<?php


namespace Teebb\CoreBundle\TextFilter;

/**
 * 换行符转为html <br>标签
 */
class Nl2brFilter implements TextFilterInterface
{
    /**
     * @var TextFilterInterface
     */
    private $nextFilter;

    public function addNextFilter(TextFilterInterface $textFilter): void
    {
        $this->nextFilter = $textFilter;
    }

    public function format(string $text): string
    {
        $result = nl2br($text);
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