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
use Teebb\CoreBundle\Annotation\Translation;

class EntityTypeEntityTypeMetadataFactory implements EntityTypeMetadataFactoryInterface
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
        $translate = $container->get('translator');

        return new EntityTypeMetadata(
            $annotation->name instanceof Translation ?
                $translate->trans($annotation->name->message, [], $annotation->name->domain) : $annotation->name,
            $annotation->alias,
            $annotation->description instanceof Translation ?
                $translate->trans($annotation->description->message, [], $annotation->description->domain) : $annotation->description,
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
        $translate = $container->get('translator');

        return new Definition(EntityTypeMetadata::class, [
            $annotation->name instanceof Translation ?
                $translate->trans($annotation->name->message, [], $annotation->name->domain) : $annotation->name,
            $annotation->alias,
            $annotation->description instanceof Translation ?
                $translate->trans($annotation->description->message, [], $annotation->description->domain) : $annotation->description,
            $reflectionClass->getName(),
            $annotation->controller,
            $annotation->repository,
            $annotation->service
        ]);
    }
}