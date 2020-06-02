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


use Symfony\Component\DependencyInjection\Definition;
use Teebb\CoreBundle\Annotation\EntityType;
use Teebb\CoreBundle\Translation\TranslatableMarkup;

class EntityTypeMetadataFactory implements EntityTypeMetadataFactoryInterface
{
    /**
     * @param \ReflectionClass $reflectionClass
     * @param EntityType $annotation
     * @return EntityTypeMetadata
     * @throws \Exception
     */
    public function create(\ReflectionClass $reflectionClass, EntityType $annotation): EntityTypeMetadata
    {
        return new EntityTypeMetadata(
            $annotation->label->get(),
            $annotation->alias,
            $annotation->description->get(),
            $annotation->controller,
            $annotation->repository,
            $annotation->entity,
            $reflectionClass->getName()
        );
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @param EntityType $annotation
     * @return Definition
     * @throws \Exception
     */
    public function createDefinition(\ReflectionClass $reflectionClass, EntityType $annotation): Definition
    {
        return new Definition(EntityTypeMetadata::class, [
            new Definition(TranslatableMarkup::class,
                [$annotation->label->message, $annotation->label->arguments, $annotation->label->domain]),
            $annotation->alias,
            new Definition(TranslatableMarkup::class,
                [$annotation->description->message, $annotation->description->arguments, $annotation->description->domain]),
            $annotation->controller,
            $annotation->repository,
            $annotation->entity,
            $reflectionClass->getName()
        ]);
    }
}