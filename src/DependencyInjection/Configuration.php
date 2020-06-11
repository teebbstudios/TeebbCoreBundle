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
                ->arrayNode('mapping')
                    ->info('The annotation reader parse path.By default, the project "src" directory and the "src/Services" directory of each bundle will be parsed')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('directories')
                                ->defaultValue([])
                                ->prototype('scalar')->end()
                        ->end()
                    ->end()
            ->end();

        $this->addTemplatesSection($rootNode);
        $this->addAssetsSection($rootNode);

        return $treeBuilder;
    }

    private function addTemplatesSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('templates')
                ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('layout')->defaultValue('@TeebbCore/standard_layout.html.twig')->cannotBeEmpty()->end()
                        ->arrayNode('types')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('list')->defaultValue('@TeebbCore/types/list/_list.html.twig')->cannotBeEmpty()->end()
                            ->end()
                        ->end()
                    ->end()
            ->end();
    }

    private function addAssetsSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('assets')
                ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('stylesheets')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('font-awesome')->defaultValue('vendor/fontawesome-free/css/all.min.css')->cannotBeEmpty()->end()
                                ->arrayNode('extra')->info('extra stylesheets to add to the page')
                                    ->defaultValue([])
                                    ->prototype('scalar')->end()
                                ->end()
                                ->scalarNode('sb-admin-2')->defaultValue('bundles/teebbcore/css/sb-admin-2.min.css')->cannotBeEmpty()->end()
                            ->end()
                        ->end()

                        ->arrayNode('javascripts')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('jquery')->defaultValue('vendor/jquery/jquery.min.js')->cannotBeEmpty()->end()
                                ->scalarNode('jquery-easing')->defaultValue('vendor/jquery-easing/jquery.easing.min.js')->cannotBeEmpty()->end()
                                ->scalarNode('bootstrap')->defaultValue('vendor/bootstrap/js/bootstrap.bundle.min.js')->cannotBeEmpty()->end()
                                ->arrayNode('extra')->info('extra javascripts to add to the page')
                                    ->defaultValue([])
                                    ->prototype('scalar')->end()
                                ->end()
                                ->scalarNode('sb-admin-2')->defaultValue('bundles/teebbcore/js/sb-admin-2.min.js')->cannotBeEmpty()->end()
                            ->end()
                        ->end()

                        ->arrayNode('extra')->info('Extra asset libraries')
                            ->useAttributeAsKey('name')
                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('css_path')->end()
                                    ->scalarNode('js_path')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
            ->end();
    }

}