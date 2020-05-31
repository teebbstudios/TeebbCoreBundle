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
use Teebb\CoreBundle\Translation\AnnotationTranslation;

class EntityTypeMetadataFactory implements EntityTypeMetadataFactoryInterface
{
    /**
     * @var AnnotationTranslation
     */
    private $translation;

    public function __construct(AnnotationTranslation $translation)
    {
        $this->translation = $translation;
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @param EntityType $annotation
     * @param ContainerBuilder $container
     * @return EntityTypeMetadata
     * @throws \Exception
     */
    public function create(\ReflectionClass $reflectionClass, EntityType $annotation, ContainerBuilder $container): EntityTypeMetadata
    {
        return new EntityTypeMetadata(
            $this->translation->trans($annotation->label),
            $annotation->alias,
            $this->translation->trans($annotation->label),
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
        $metadata = $this->create($reflectionClass, $annotation, $container);

        return new Definition(get_class($metadata), [
            $metadata->getLabel(),
            $metadata->getAlias(),
            $metadata->getDescription(),
            $metadata->getEntityClassName(),
            $metadata->getController(),
            $metadata->getRepository(),
            $metadata->getService()
        ]);

    }
}