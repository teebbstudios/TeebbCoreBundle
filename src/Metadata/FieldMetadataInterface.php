<?php


namespace Teebb\CoreBundle\Metadata;

use Teebb\CoreBundle\Translation\TranslatableMarkup;

/**
 * 字段类型
 */
interface FieldMetadataInterface
{
    /**
     * 获取字段id
     * @return string
     */
    public function getId(): string;

    /**
     * 获取字段标题
     * @return TranslatableMarkup
     */
    public function getLabel(): TranslatableMarkup;

    /**
     * 获取字段描述
     * @return TranslatableMarkup
     */
    public function getDescription(): TranslatableMarkup;

    /**
     * 获取字段分组
     * @return TranslatableMarkup
     */
    public function getCategory(): TranslatableMarkup;

    /**
     * 获取字段Entity类名
     * @return string
     */
    public function getEntity(): string;

    /**
     * 获取字段配置表单Type类名
     * @return string
     */
    public function getFieldFormConfigType(): string;

    /**
     * 获取字段表单Type类名
     * @return string
     */
    public function getFieldFormType(): string;
}