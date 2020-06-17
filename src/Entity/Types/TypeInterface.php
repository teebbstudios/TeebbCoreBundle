<?php


namespace Teebb\CoreBundle\Entity\Types;

/**
 * 类型Entity需要实现的接口必须要提供的方法
 */
interface TypeInterface
{
    /**
     * @return string|null
     */
    public function getLabel(): ?string;

    /**
     * @param string $label
     */
    public function setLabel(string $label): void;

    /**
     * @return string|null
     */
    public function getBundle(): ?string;

    /**
     * @param string $bundle
     */
    public function setBundle(string $bundle): void;

    /**
     * @return string|null
     */
    public function getAlias(): ?string;

    /**
     * @param string $alias
     */
    public function setAlias(string $alias): void;
}