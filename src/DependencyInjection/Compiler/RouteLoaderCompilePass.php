<?php
/**
 * This file is part of the TeebbCoreBundle package.
 *
 * Author: Quan Weiwei <qww.zone@gmail.com>
 * Date: 2020/5/29
 * (c) teebbstudios
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Teebb\CoreBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RouteLoaderCompilePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $entityTypeServices = $container->findTaggedServiceIds('teebb.entity_type');
        $entityRouteLoaderDefinition = $container->getDefinition('teebb.core.route.entity_type_route_loader');
        $entityRouteLoaderDefinition->setArgument(0, $entityTypeServices);
    }
}