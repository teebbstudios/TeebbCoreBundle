<?php
/**
 * This file is part of the TeebbCoreBundle package.
 *
 * Author: Quan Weiwei <qww.zone@gmail.com>
 * Date: 2020/5/20
 * (c) teebbstudios
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Teebb\CoreBundle\Annotation;

/**
 * 所有可配置字段的内容实体类型EntityType需添加此注解.例如: Content、Taxonomy、Comment、
 * User都使用此Annotation,以配置不同字段.
 *
 * @Annotation
 * @Target({"CLASS"})
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class EntityType
{
    /**
     * 简短描述内容实体类型
     */
    public $description;
}