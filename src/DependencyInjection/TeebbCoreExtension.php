<?php
/**
 * This file is part of the TeebbCoreBundle package.
 *
 * Author: Quan Weiwei <qww.zone@gmail.com>
 * Date: 2020/5/20
 * (c) teebbstudios
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Teebb\CoreBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;


class TeebbCoreExtension extends Extension
{
    /**
     * @param array $configs The configurations being loaded
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $resources = [
            'blocks',
            'commands',
            'controllers',
            'forms',
            'events',
            'routes',
            'services',
            'voters'
        ];

        foreach ($resources as $resource) {
            $loader->load($resource . '.xml');
        }

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $mappingDirectories = array_merge($this->getBundlesEntityPaths($container), $config['mapping']['directories']);
        if (empty($mappingDirectories)) {
            throw new InvalidArgumentException('TeebbCoreBundle configure key "[\'mapping\'][\'directories\']" can not empty. Please config it.');
        }

        $container->setParameter('teebb.core.mapping.directories', $mappingDirectories);

        $this->setTemplateRegistryArguments($container, $config);

        $flySystemServiceId = $config['fly_system']['service'];

        //设置FileController FileSystemInterface构造参数
        $fileControllerDefinition = $container->getDefinition('teebb.core.controller.file_controller');
        $fileControllerDefinition->setArgument(3, new Reference($flySystemServiceId));
        $fileControllerDefinition->setArgument(4, $config['fly_system']['default_upload_dir']);
        $fileControllerDefinition->setArgument(5, $config['fly_system']['root_host_url']);

        //设置twig global构造参数
        $twigGlobalDefinition = $container->getDefinition('teebb.core.twig.global');
        $twigGlobalDefinition->setArgument(3, $config['fly_system']['root_host_url']);

        //设置文件显示twig Extension 构造参数
        $uploadFileShowExtensionDefinition = $container->getDefinition('teebb.core.twig.upload_file_show');
        $uploadFileShowExtensionDefinition->setArguments([
            new Reference($flySystemServiceId),
            $config['fly_system']['root_host_url']
        ]);

        //设置ReferenceImageFieldType构造参数
        $referenceImageFieldType = $container->getDefinition('teebb.core.form.reference_image_type');
        $referenceImageFieldType->setArgument(0, new Reference($flySystemServiceId));

        //设置文本过滤器
        $container->setParameter('teebb.core.formatter.filter_settings', $config['filter_settings']);

        //设置边栏菜单
        $container->setParameter('teebb.core.menu.sidebar_menu', $config['side_menu']);

        //设置Dashboard首页blocks
        $container->setParameter('teebb.core.dashboard.blocks', $config['blocks']);

        //将邮件发送人信息添加进parameter
        $container->setParameter('teebb.core.mailer.from_email',  $config['from_email']);
    }

    /**
     * 其他Bundle的Entity目录参与获取EntityType Annotation
     *
     * @param ContainerBuilder $container
     * @return array
     */
    private function getBundlesEntityPaths(ContainerBuilder $container)
    {
        $bundlesEntityPaths = [];

        foreach ($container->getParameter('kernel.bundles_metadata') as $bundle) {
            $paths = [];
            $dirname = $bundle['path'];

            $paths[] = "$dirname/Services";

            foreach ($paths as $path) {
                if ($container->fileExists($path, false)) {
                    $bundlesEntityPaths[] = $path;
                }
            }
        }

        return $bundlesEntityPaths;
    }

    /**
     * 设置TemplateRegistry构造参数
     * @param ContainerBuilder $container
     * @param array $config
     */
    private function setTemplateRegistryArguments(ContainerBuilder $container, array $config)
    {
        $templateRegistryDefinition = $container->getDefinition('teebb.core.template.registry');

        $templateRegistryDefinition->setArguments([
            $config['templates'],
            $config['assets']['stylesheets'],
            $config['assets']['javascripts'],
            $config['assets']['extra'],
        ]);
    }
}