<?php


namespace Teebb\CoreBundle\Entity\Fields\Configuration;

/**
 * 分离不同字段的设置信息为单独的类
 */
interface FieldDepartConfigurationInterface
{
    /**
     * 获取$value字段doctrine mapping type
     *
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @param string $description
     */
    public function setDescription(string $description): void;

    /**
     * @return bool
     */
    public function isRequired(): bool;

    /**
     * @param bool $required
     */
    public function setRequired(bool $required): void;

    /**
     * @return int
     */
    public function getLimit(): int;

    /**
     * @param int $limit
     */
    public function setLimit(int $limit): void;

}