<?php
/**
 * This file is part of the TeebbCoreBundle package.
 *
 * Author: Quan Weiwei <qww.zone@gmail.com>
 * Date: 2020/5/28
 * (c) teebbstudios
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Teebb\CoreBundle\Metadata;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Teebb\CoreBundle\Annotation\EntityType;

/**
 * 获取EntityType注解，创建EntityTypeMetadata
 */
interface EntityTypeMetadataFactoryInterface
{
    public function create(\ReflectionClass $reflectionClass, EntityType $annotation, ContainerBuilder $container): EntityTypeMetadata;

    public function createDefinition(\ReflectionClass $reflectionClass, EntityType $annotation, ContainerBuilder $container): Definition;
}