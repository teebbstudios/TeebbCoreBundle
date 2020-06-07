<?php


namespace Teebb\CoreBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class GlobalVariablesCompilePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->getDefinition('twig')
            ->addMethodCall('addGlobal', ['teebb_core', new Reference('teebb.core.twig.global')]);
    }
}