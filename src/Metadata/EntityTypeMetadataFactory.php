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
use Teebb\CoreBundle\Translation\TransUtil;

class EntityTypeMetadataFactory implements EntityTypeMetadataFactoryInterface
{
    /**
     * @param \ReflectionClass $reflectionClass
     * @param EntityType $annotation
     * @param ContainerBuilder $container
     * @return EntityTypeMetadata
     * @throws \Exception
     */
    public function create(\ReflectionClass $reflectionClass, EntityType $annotation, ContainerBuilder $container): EntityTypeMetadata
    {
        $translator = $container->get('translator');

        return new EntityTypeMetadata(
            (new TransUtil($annotation->label))->trans($translator),
            $annotation->alias,
            (new TransUtil($annotation->description))->trans($translator),
            $reflectionClass->getName(),
            $annotation->controller,
            $annotation->repository,
            $annotation->service
        );
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @param EntityType $annotation
     * @param ContainerBuilder $container
     * @return Definition
     * @throws \Exception
     */
    public function createDefinition(\ReflectionClass $reflectionClass, EntityType $annotation, ContainerBuilder $container): Definition
    {
        $translator = $container->get('translator');

        return new Definition(EntityTypeMetadata::class, [
            (new TransUtil($annotation->label))->trans($translator),
            $annotation->alias,
            (new TransUtil($annotation->description))->trans($translator),
            $reflectionClass->getName(),
            $annotation->controller,
            $annotation->repository,
            $annotation->service
        ]);
    }
}