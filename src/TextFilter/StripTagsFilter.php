<?php


namespace Teebb\CoreBundle\TextFilter;


/**
 * 过滤仅允许的html标签
 */
class StripTagsFilter implements TextFilterInterface
{
    /**
     * @var TextFilterInterface
     */
    private $nextFilter;

    /**
     * 过滤器过滤内容时额外的参数，比如 允许的html标签
     * @var string
     */
    private $extra;

    /**
     * @return string
     */
    public function getExtra(): string
    {
        return $this->extra;
    }

    /**
     * @param string $extra
     */
    public function setExtra(string $extra): void
    {
        $this->extra = $extra;
    }

    public function addNextFilter(TextFilterInterface $textFilter): void
    {
        $this->nextFilter = $textFilter;
    }

    /**
     * 过滤仅允许的html标签
     * @param string $text
     * @return string
     */
    public function format(string $text): string
    {
        $result = strip_tags($text, $this->extra);

        if (null !== $this->nextFilter) {
            $result = $this->nextFilter->format($result);
        }

        return $result;
    }

    public function hasExtra(): bool
    {
        return true;
    }

}