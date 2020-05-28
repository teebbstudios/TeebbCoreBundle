<?php


namespace Teebb\CoreBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        if (\method_exists(TreeBuilder::class, 'getRootNode')) {
            $treeBuilder = new TreeBuilder('teebb_core');
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $treeBuilder = new TreeBuilder();
            $rootNode = $treeBuilder->root('teebb_core');
        }

        $rootNode
            ->children()
                ->arrayNode('mapping')->info('The annotation reader parse path.')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('directories')
                                ->defaultValue([
                                    '%kernel.project_dir%/src/Entity'
                                ])
                                ->prototype('scalar')->end()
                        ->end()
                    ->end()
            ->end();

        return $treeBuilder;
    }


}