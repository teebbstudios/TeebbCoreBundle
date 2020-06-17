<?php
/**
 * This file is part of the TeebbCoreBundle package.
 *
 * Author: Quan Weiwei <qww.zone@gmail.com>
 * Date: 2020/5/26
 * (c) teebbstudios
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Teebb\CoreBundle\Metadata;


use Teebb\CoreBundle\Translation\TranslatableMarkup;

/**
 * Types Annotation Metadata接口
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
interface EntityTypeMetadataInterface
{
    /**
     * 获取EntityType标题
     *
     * @return TranslatableMarkup
     */
    public function getLabel(): TranslatableMarkup;

    /**
     * 获取EntityType Bundle别名用于Route路径
     *
     * @return string
     */
    public function getBundle(): string;

    /**
     * 获取EntityType描述
     *
     * @return TranslatableMarkup
     */
    public function getDescription(): TranslatableMarkup;

    /**
     * 获取EntityType Controller全路径类名
     *
     * @return string
     */
    public function getController(): string;

    /**
     * 获取EntityType Repository全路径类名
     *
     * @return string
     */
    public function getRepository(): string;

    /**
     * 获取 Entity 全路径类名
     *
     * @return string
     */
    public function getEntity(): string;

    /**
     * 获取EntityType Service id
     *
     * @return string
     */
    public function getService(): string;

    /**
     * 获取Types表单配置
     * @return array
     */
    public function getFormSettings(): array;
}