<?php


namespace Teebb\CoreBundle\DependencyInjection\Compiler;


use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Reference;
use Teebb\CoreBundle\Annotation\TeebbAnnotationInterface;

trait RegisterRepositoryTrait
{
    public function addRepository(TeebbAnnotationInterface $annotation, ContainerBuilder $container)
    {
        $repositoryClassName = $annotation->repository;

        try {
            $reflectionRepositoryClassName = $container->getReflectionClass($repositoryClassName, false);
        } catch (\ReflectionException $e) {
            throw new InvalidArgumentException(sprintf('Class "%s" used for service "%s" cannot be found.', $repositoryClassName, $repositoryClassName));
        }

        $repositoryDefinition = $this->createRepositoryDefinition($reflectionRepositoryClassName, $annotation);

        $container->setDefinition($reflectionRepositoryClassName->getName(), $repositoryDefinition);
    }

    public function createRepositoryDefinition(\ReflectionClass $reflectionRepositoryClassName, TeebbAnnotationInterface $annotation): Definition
    {
        $definition = new Definition($reflectionRepositoryClassName->getName());

        $definition->setArguments([
            new Reference('doctrine.orm.entity_manager'),
            $this->getClassMetadataDefinition($annotation),
        ]);
        $definition->setPublic(true);

        return $definition;
    }

    public function getClassMetadataDefinition(TeebbAnnotationInterface $annotation): Definition
    {
        return new Definition(ClassMetadata::class, [
            $annotation->entity
        ]);
    }
}