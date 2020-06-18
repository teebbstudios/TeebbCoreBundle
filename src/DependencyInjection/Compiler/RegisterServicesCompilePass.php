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
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Reference;
use Teebb\CoreBundle\Annotation\EntityType;
use Teebb\CoreBundle\Annotation\FieldType;
use Teebb\CoreBundle\Mapping\AnnotationExtractorTrait;
use Teebb\CoreBundle\Mapping\ReflectionClassRecursiveIterator;

/**
 * Class EntityTypeCompilePass 注册EntityType注释的类到Container
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class RegisterServicesCompilePass implements CompilerPassInterface
{
    private const ENTITY_TYPE_TAG = 'teebb.entity_type';

    private const FIELD_TAG = 'teebb.field';

    use AnnotationExtractorTrait;

    use MetadataTrait;
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
            $this->createEntityTypeServices($reflectionClass, $container);
            $this->createFieldServices($reflectionClass, $container);
        }
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @param ContainerBuilder $container
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function createEntityTypeServices(\ReflectionClass $reflectionClass, ContainerBuilder $container)
    {
        $this->reader = $this->reader ?? $container->get('annotation_reader');

        foreach ($this->readAnnotation($reflectionClass, $this->reader, EntityType::class)
                 as $reflectionClass => $annotation) {

            if (!$annotation instanceof EntityType) {
                continue;
            }

            if (false === strpos($annotation->entity, "\\")) {
                throw new \InvalidArgumentException(sprintf('The class "%s" annotation "EntityType" property "entity" must be Full Qualified Class Name.',
                    $reflectionClass->getName()));
            }

            if (false === strpos($annotation->repository, "\\")) {
                throw new \InvalidArgumentException(sprintf('The class "%s" annotation "EntityType" property "repository" must be Full Qualified Class Name.',
                    $reflectionClass->getName()));
            }

            $id = $this->generateServiceId('teebb.core.entity_type.', $reflectionClass->getName());

            if ($container->has($id)) {
                continue;
            }

            if (null === $entityTypeServiceReflectionClass = $container->getReflectionClass($reflectionClass->getName(), false)) {
                throw new InvalidArgumentException(sprintf('Class "%s" used for service "%s" cannot be found.', $reflectionClass->getName(), $id));
            }

            $definition = new Definition($entityTypeServiceReflectionClass->getName());
            $definition->setAutoconfigured(true);
            $definition->addTag(self::ENTITY_TYPE_TAG);
            $definition->setAutowired(true);
            $definition->setPublic(true);

            $definition->setArgument(0, new Reference('teebb.core.route.types_builder'));
            $definition->setArgument(1, new Reference('service_container'));
            $definition->setArgument(2, new Reference('teebb.core.route.path_info_generator'));
            $definition->setArgument(3, new Reference('doctrine.orm.default_entity_manager'));

            $metadataDefinition = $this->createEntityTypeMetadataDefinition($reflectionClass, $annotation);
            $definition->addMethodCall('setEntityTypeMetadata', [$metadataDefinition]);

            $container->setDefinition($id, $definition);
            $container->setAlias($entityTypeServiceReflectionClass->getName(), new Alias($id, true));

        }
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @param ContainerBuilder $container
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function createFieldServices(\ReflectionClass $reflectionClass, ContainerBuilder $container)
    {
        $this->reader = $this->reader ?? $container->get('annotation_reader');

        foreach ($this->readAnnotation($reflectionClass, $this->reader, FieldType::class)
                 as $reflectionClass => $annotation) {

            if (!$annotation instanceof FieldType) {
                continue;
            }

            if (false === strpos($annotation->entity, "\\")) {
                throw new \InvalidArgumentException(sprintf('The class "%s" annotation "FieldType" property "entity" must be Full Qualified Class Name.',
                    $reflectionClass->getName()));
            }

            //Field service id
            $id = 'teebb.core.field.' . $annotation->id;

            if ($container->has($id)) {
                continue;
            }

            if (null === $fieldServiceReflectionClass = $container->getReflectionClass($reflectionClass->getName(), false)) {
                throw new InvalidArgumentException(sprintf('Class "%s" used for service "%s" cannot be found.', $reflectionClass->getName(), $id));
            }

            $definition = new Definition($fieldServiceReflectionClass->getName());
            $definition->setAutoconfigured(true);
            $definition->addTag(self::FIELD_TAG, ['type' => $annotation->type]);
            $definition->setAutowired(true);
            $definition->setPublic(true);

            $metadataDefinition = $this->createFieldTypeMetadataDefinition($annotation);
            $definition->addMethodCall('setFieldMetadata', [$metadataDefinition]);

            $container->setDefinition($id, $definition);
        }
    }


}