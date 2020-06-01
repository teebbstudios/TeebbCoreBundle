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
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Teebb\CoreBundle\AbstractService\EntityTypeInterface;
use Teebb\CoreBundle\Mapping\ReflectionClassRecursiveIterator;


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
            'services',
            'routes',
            'controllers'
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

    }

    /**
     * 其他Bundle的Entity目录参与获取EntityType Annotation
     *
     * @param ContainerBuilder $container
     * @return array
     */
    public function getBundlesEntityPaths(ContainerBuilder $container)
    {
        $bundlesEntityPaths = [];

        foreach ($container->getParameter('kernel.bundles_metadata') as $bundle) {
            $paths = [];
            $dirname = $bundle['path'];

            $paths[] = "$dirname/Entity";

            foreach ($paths as $path) {
                if ($container->fileExists($path, false)) {
                    $bundlesEntityPaths[] = $path;
                }
            }
        }

        return $bundlesEntityPaths;
    }

}