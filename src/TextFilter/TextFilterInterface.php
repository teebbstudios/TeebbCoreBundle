<?php


namespace Teebb\CoreBundle\TextFilter;


/**
 * 文本过滤器接口，责任链模式，按顺序过滤内容
 */
interface TextFilterInterface
{
    /**
     * 添加下一个链继续处理文本
     * @param TextFilterInterface $textFilter
     * @return mixed
     */
    public function addNextFilter(TextFilterInterface $textFilter): void;

    /**
     * 过滤及格式化文本
     * @param string $text
     * @return string
     */
    public function format(string $text): string;

    /**
     * 当前过滤器是否需要额外的过滤参数,如果有则需要添加$extra属性并添加setExtra() getExtra()方法。
     * @return bool
     */
    public function hasExtra(): bool;
}