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
use Doctrine\ORM\Mapping\Annotation;

/**
 * 所有可配置字段的内容实体类型EntityType需添加此注解.例如: Content、Taxonomy、Comment、
 * User都使用此Annotation,以配置不同字段.
 *
 * @Annotation
 * @Target({"CLASS"})
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class EntityType implements TeebbAnnotationInterface
{
    /**
     * 类型标题
     * @Required
     */
    public $label;

    /**
     * 类型bundle别名用于URL构建
     * @Required
     * @var string
     */
    public $bundle;

    /**
     * 简短描述类型
     */
    public $description;

    /**
     * 当前类型的Controller类
     * @Required
     * @var string
     */
    public $controller;

    /**
     * 当前类型的Repository类
     * @Required
     * @var string
     */
    public $repository;

    /**
     * 当前类型的Entity类
     * @Required
     * @var string
     */
    public $typeEntity;

    /**
     * 内容Entity类
     * @Required
     * @var string
     */
    public $entity;

    /**
     * 设置EntityType表单
     */
    public $form;

    /**
     * 配置不同action页面的head、dropdown actions及action图标
     * @var array
     */
    public $actions;
}