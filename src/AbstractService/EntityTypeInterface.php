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

namespace Teebb\CoreBundle\AbstractService;

use Teebb\CoreBundle\Metadata\EntityTypeMetadataInterface;
use Teebb\CoreBundle\Repository\RepositoryInterface;
use Teebb\CoreBundle\Route\EntityTypeRouteCollection;
use Teebb\CoreBundle\Route\PathInfoGeneratorInterface;

/**
 * 所有可配置字段的内容实体类型EntityType需实现此接口.
 *
 * *原理:*
 * 假如我们使用Symfony开发传统的CMS系统.您需要一个Article类型的内容,Article类可能包含title、body、tags、images等等属性,
 * 这些属性一般固定限制了Article的表单,或者说Article的类型是固定的,您无法在Article中方便的添加需要的属性,例如想在Article
 * 中添加summary,您就需要在Article类中添加summary属性,然后修改数据库,修改表单添加summary行.
 *
 * 如果您想添加News类型的内容,就需要新建一个News类添加对应需要的属性,然后修改数据库.因为Symfony的功能强大,您可能花一会功夫就
 * 完成了需要的功能.但是如果您的CMS是给那些没有开发经验的用户使用,他们不明白什么是Symfony的开发过程,该怎么办呢?
 *
 * 这就需要一款灵活的、扩展性强大的CMS系统了.用户甚至仅需在后台通过配置就可以生成需要的内容类型.Teebb为了增强CMS的扩展性参考
 * Drupal的功能进行设计,我们可以把有共同特点的类抽取成一个通用些的层并起一些名字.我们在这里提出一些概念:
 *
 * 我们把Article、News之类的类型称为*内容实体类型ContentEntityType*.
 * 把Article、News中这些常用类型的属性进行抽取每个属性叫作*字段Field*.例如: title就是string类型的字段, body就是text类型的字段.
 *
 * *内容实体类型EntityType*: 组合不同类型的字段Field, 在添加内容实体Content时根据字段Field生成不同的表单与存储.
 * *内容实体Content*: 针对不同的内容实体类型保存的不同内容实体.
 * *字段Field*: 不同的字段提供不同的表单显示与存储.
 *
 * EntityTypeInterface提供内容实体类型EntityType的通用操作, 不同的内容实体类型EntityType需要实现此接口,
 * Teebb中Content、Taxonomy、User、Comment实现此接口.
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
interface EntityTypeInterface
{
    /**
     * 设置内容实体类型元数据
     *
     * @param EntityTypeMetadataInterface $metadata
     */
    public function setEntityTypeMetadata(EntityTypeMetadataInterface $metadata): void;

    /**
     * 获取内容实体类型元数据
     *
     * @return EntityTypeMetadataInterface
     */
    public function getEntityTypeMetadata(): EntityTypeMetadataInterface;

    /**
     * 获取EntityType Repository
     *
     * @return RepositoryInterface
     */
    public function getEntityTypeRepository(): RepositoryInterface;

    /**
     * 获取当前内容实体类型的所有routes
     *
     * @return EntityTypeRouteCollection
     */
    public function getRoutes(): EntityTypeRouteCollection;

    /**
     * 当前内容实体类型创建routes
     */
    public function buildRoutes(): void;

    /**
     * 判断当前类型是否有此route name
     *
     * @param string $serviceId
     * @param string $name action name，需要处理为route name
     * @return bool
     */
    public function hasRoute(string $serviceId, string $name): bool;

    /**
     * 根据用户权限获取所有可用操作
     */
    public function getActionButtons(): array;

    /**
     * @return PathInfoGeneratorInterface
     */
    public function getPathInfoGenerator(): PathInfoGeneratorInterface;

    /**
     * 获取entity type全类名
     * @return string
     */
    public function getTypeEntityClass(): string;

    /**
     * 获取保存内容的具体entity全类名
     * @return string
     */
    public function getEntityClassName():string;

    /**
     * 获取类型注释中的Bundle别名
     * @return string
     */
    public function getBundle():string;

    /**
     * 根据action名称获取route名称
     * @param string $actionName
     * @return string
     */
    public function getRouteName(string $actionName): string;

    /**
     * 获取对应的Entity FormType
     * @return string
     */
    public function getEntityFormType():string;
}