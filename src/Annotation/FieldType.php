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
 * 所有可配置的字段Field添加此注解.
 *
 * @Annotation
 * @Target({"CLASS"})
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class FieldType
{
    /**
     * @var string
     */
    public $id;

    public $label;

    public $description;

    /**
     * 字段类型的分组名称
     */
    public $category;


}