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
                ->end()

                ->arrayNode('fly_system')
                    ->info('The file system parameters.')
                    ->children()
                        ->scalarNode('service')->defaultValue('oneup_flysystem.default_filesystem_filesystem')->cannotBeEmpty()->end()
                        ->scalarNode('default_upload_dir')->defaultValue('[%date.Year~"-"~date.month~"-"~date.day]')->cannotBeEmpty()->end()
                        ->scalarNode('root_host_url')->isRequired()->end()
                    ->end()
                ->end()

            ->end();

        $this->addTemplatesSection($rootNode);
        $this->addAssetsSection($rootNode);
        $this->addTextFilterSection($rootNode);

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

                        ->arrayNode('content')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('list_types')->defaultValue('@TeebbCore/content/list/list_types.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('index')->defaultValue('@TeebbCore/content/list/_list.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('create')->defaultValue('@TeebbCore/content/form/_form.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('update')->defaultValue('@TeebbCore/content/form/_form.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('delete')->defaultValue('@TeebbCore/content/form/_delete_form.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('show')->defaultValue('@TeebbCore/content/show.html.twig')->cannotBeEmpty()->end()
                            ->end()
                        ->end()

                        ->arrayNode('types')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('list')->defaultValue('@TeebbCore/types/list/_list.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('create')->defaultValue('@TeebbCore/types/form/_form.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('update')->defaultValue('@TeebbCore/types/form/_form.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('delete')->defaultValue('@TeebbCore/types/form/_delete_form.html.twig')->cannotBeEmpty()->end()
                            ->end()
                        ->end()

                        ->arrayNode('fields')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('select_fields')->defaultValue('@TeebbCore/fields/form/select_fields.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('list_fields')->defaultValue('@TeebbCore/fields/list/list_fields.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('update_field')->defaultValue('@TeebbCore/fields/form/_form.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('delete_field')->defaultValue('@TeebbCore/fields/form/_delete_form.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('display_field')->defaultValue('@TeebbCore/fields/form/_display_field_form.html.twig')->cannotBeEmpty()->end()
                            ->end()
                        ->end()

                        ->arrayNode('formatter')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('index')->defaultValue('@TeebbCore/formatter/list/_list.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('create')->defaultValue('@TeebbCore/formatter/form/_form.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('update')->defaultValue('@TeebbCore/formatter/form/_form.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('delete')->defaultValue('@TeebbCore/formatter/form/_delete_form.html.twig')->cannotBeEmpty()->end()
                            ->end()
                        ->end()

                        ->arrayNode('terms')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('index_term')->defaultValue('@TeebbCore/terms/list/_list.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('create_term')->defaultValue('@TeebbCore/terms/form/_form.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('update_term')->defaultValue('@TeebbCore/terms/form/_form.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('delete_term')->defaultValue('@TeebbCore/terms/form/_delete_form.html.twig')->cannotBeEmpty()->end()
                            ->end()
                        ->end()

                        ->arrayNode('comment')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('index')->defaultValue('@TeebbCore/comment/list/_list.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('form')->defaultValue('@TeebbCore/comment/form/_form.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('delete_form')->defaultValue('@TeebbCore/comment/form/_delete_form.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('reply_form')->defaultValue('@TeebbCore/comment/form/_reply_comment_form.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('index_comments')->defaultValue('@TeebbCore/comment/list/index_comments.html.twig')->cannotBeEmpty()->end()
                            ->end()
                        ->end()

                        ->arrayNode('security')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('login')->defaultValue('@TeebbCore/security/login.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('register')->defaultValue('@TeebbCore/security/register.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('register_confirm')->defaultValue('@TeebbCore/security/register_confirm.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('resetting_request')->defaultValue('@TeebbCore/security/resetting_request.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('resetting')->defaultValue('@TeebbCore/security/resetting.html.twig')->cannotBeEmpty()->end()
                            ->end()
                        ->end()

                        ->arrayNode('user')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('people_index')->defaultValue('@TeebbCore/user/list/_user_list.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('group_index')->defaultValue('@TeebbCore/user/list/_group_list.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('group_form')->defaultValue('@TeebbCore/user/form/group_form.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('group_delete')->defaultValue('@TeebbCore/user/form/_delete_form.html.twig')->cannotBeEmpty()->end()
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
                                ->scalarNode('sb-admin-2')->defaultValue('bundles/teebbcore/js/sb-admin-2.js')->cannotBeEmpty()->end()
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

    private function addTextFilterSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('filter_settings')->info('Text filters settings')
                    ->useAttributeAsKey('filter_name')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('filter_class')->end()
                            ->scalarNode('filter_label')->end()
                            ->scalarNode('extra_form_type')->end()
                            ->scalarNode('extra_label')->end()
                            ->scalarNode('extra_help')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}