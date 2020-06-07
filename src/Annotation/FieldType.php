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
class FieldType implements TeebbAnnotationInterface
{
    /**
     * @var string
     * @Required()
     */
    public $id;

    /**
     * @Required()
     */
    public $label;

    public $description;

    /**
     * @var string
     */
    public $type;
    
    /**
     * 字段类型的分组名称
     */
    public $category;

    /**
     * 字段类型对应的实体类
     *
     * @var string
     */
    public $entity;

}