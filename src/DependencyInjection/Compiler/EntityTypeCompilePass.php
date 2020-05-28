<?php
/**
 * This file is part of the TeebbCoreBundle package.
 *
 * Author: Quan Weiwei <qww.zone@gmail.com>
 * Date: 2020/5/26
 * (c) teebbstudios
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Teebb\CoreBundle\DependencyInjection\Compiler;


use Doctrine\Common\Annotations\Reader;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Teebb\CoreBundle\Annotation\EntityType;
use Teebb\CoreBundle\Mapping\AnnotationExtractorTrait;
use Teebb\CoreBundle\Mapping\ReflectionClassRecursiveIterator;

/**
 * Class EntityTypeCompilePass 注册EntityType注释的类到Container
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class EntityTypeCompilePass implements CompilerPassInterface
{
    use AnnotationExtractorTrait;

    private const ENTITY_TYPE_TAG = 'teebb.entity_type';

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function process(ContainerBuilder $container): void
    {
        $mappingDirectories = $container->getParameter('teebb.core.mapping.directories');

        foreach (ReflectionClassRecursiveIterator::getReflectionClassesFromDirectories($mappingDirectories)
                 as $className => $reflectionClass) {
            $this->createEntityTypeDefinition($reflectionClass, $container);
        }

    }

    /**
     * 创建内容实体类型Service并注册到container
     *
     * @param \ReflectionClass $reflectionClass
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function createEntityTypeDefinition(\ReflectionClass $reflectionClass, ContainerBuilder $container): void
    {
        $this->reader = $this->reader ?? $container->get('annotation_reader');

        $entityTypeMetadataFactory = $container->get('teebb.core.metadata.entity_type_metadata_factory');

        foreach ($this->readAnnotation($reflectionClass, $this->reader, EntityType::class)
                 as $reflectionClass => $annotation) {

            if (null !== $container->get($reflectionClass->getName(), ContainerInterface::NULL_ON_INVALID_REFERENCE)) {
                continue;
            }

            if (null === $entityTypeReflectionClass = $container->getReflectionClass($reflectionClass->getName(), false)) {
                throw new InvalidArgumentException(sprintf('Class "%s" used for service "%s" cannot be found.', $reflectionClass->getName(), $reflectionClass->getName()));
            }

            if ($annotation instanceof EntityType) {
                $definition = new Definition($reflectionClass->getName());
                $definition->setAutoconfigured(true);
                $definition->addTag(self::ENTITY_TYPE_TAG);
                $definition->setAutowired(true);
                $definition->setPublic(true);

                $definition->setArgument(0, $container->getDefinition('teebb.core.route.types_builder'));

                $container->setDefinition($reflectionClass->getName(), $definition);

                $metadataDefinition = $entityTypeMetadataFactory->createDefinition($reflectionClass, $annotation, $container);
                $definition->addMethodCall('setEntityTypeMetadata', [$metadataDefinition]);

            }

        }
    }
}