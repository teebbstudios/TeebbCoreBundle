<?php


namespace Teebb\CoreBundle\Templating;

/**
 * 管理所有Assets
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
interface TemplateRegistryInterface
{
    /**
     * 设置额外的StyleSheets
     * @param array $extra
     */
    public function setExtraStyleSheets(array $extra): void;

    /**
     * 设置额外的JavaScripts
     * @param array $extra
     */
    public function setExtraJavaScripts(array $extra): void;

    /**
     * 获取模板
     * @param string $name
     * @param string|null $category 模板分组
     * @return string
     */
    public function getTemplate(string $name, string $category = null): string;

    /**
     * @return array
     */
    public function getAllTemplates(): array;

    /**
     * 获取所有StyleSheets，额外StyleSheets显示在sb-admin-2.css之前
     * @return array
     */
    public function getAllStyleSheets(): array;

    /**
     * 获取所有JavaScripts，额外JavaScripts显示在sb-admin-2.js之前
     * @return array
     */
    public function getAllJavaScripts(): array;

    /**
     * @return array
     */
    public function getStylesheets(): array;

    /**
     * @return array
     */
    public function getJavascripts(): array;

    /**
     * @return array
     */
    public function getExtraLibraries(): array;

    /**
     * @param array $extraLibraries
     */
    public function setExtraLibraries(array $extraLibraries): void;

    /**
     * 获取指定extra库
     * @param string $name
     * @return array
     */
    public function getExtraLibrary(string $name): array;

    /**
     * 添加页面额外的库资源
     * @param array $extraNames 额外Extra库名称数组
     */
    public function addExtraToStyleSheetsAndJavaScripts(array $extraNames): void;
}