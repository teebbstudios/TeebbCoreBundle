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

/**
 * EntityType Annotation Metadata接口
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
interface EntityTypeMetadataInterface
{
    /**
     * 获取EntityType 名称
     *
     * @return string
     */
    public function getName(): string;

    /**
     * 获取EntityType 别名 用于Route路径中
     *
     * @return string
     */
    public function getAlias(): string;

    /**
     * 获取EntityType 描述
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * 获取EntityType 全路径类名
     *
     * @return string
     */
    public function getEntityTypeClassName(): string;

    /**
     * 获取EntityType Controller 全路径类名
     *
     * @return string
     */
    public function getController(): string;

    /**
     * 获取EntityType Repository 全路径类名
     *
     * @return string
     */
    public function getRepository(): string;

}