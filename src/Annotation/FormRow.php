<?php
/**
 * This file is part of the TeebbCoreBundle package.
 *
 * Author: Quan Weiwei <qww.zone@gmail.com>
 * Date: 2020/5/21
 * (c) teebbstudios
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Teebb\CoreBundle\Annotation;


/**
 * @Annotation
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class FormRow
{
    /**
     * Entity 属性
     * @var string
     */
    public $property;

    /**
     * 当前行是否为别名row
     * @var bool
     */
    public $isAlias = false;

    /**
     * 当前属性使用的FormType Class全类名
     * @var string
     */
    public $formType;

    /**
     * FormType options
     * @var array
     */
    public $options;

}