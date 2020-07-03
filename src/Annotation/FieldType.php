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

use Doctrine\Common\Annotations\Annotation\Required;

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
     * @Required
     */
    public $id;

    /**
     * @Required()
     */
    public $label;

    /**
     * @deprecated 
     */
    public $description;

    /**
     * @Required
     * @var string
     */
    public $type;
    
    /**
     * 字段类型的分组名称
     *
     * @deprecated
     */
    public $category;

    /**
     * 字段类型对应的实体类
     * @Required
     * @var string
     */
    public $entity;

    /**
     * 字段配置表单的entity类名
     *
     * @var string
     */
    public $formConfigEntity;

    /**
     * 字段配置表单Type
     * @Required
     * @var string
     */
    public $formConfigType;

    /**
     * 字段表单Type
     * @Required
     * @var string
     */
    public $formType;
}