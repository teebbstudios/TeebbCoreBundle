<?php
/**
 * This file is part of the TeebbCoreBundle package.
 *
 * Author: Quan Weiwei <qww.zone@gmail.com>
 * Date: 2020/5/27
 * (c) teebbstudios
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Teebb\CoreBundle\Route;


use Symfony\Component\Routing\Route;

class RouteFactory implements RouteFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createRoute(string $path, array $defaults = [], array $requirements = [],
                                array $options = [], string $host = '', array $schemes = [],
                                array $methods = [], string $condition = ''): Route
    {
        return new Route($path, $defaults, $requirements, $options, $host, $schemes, $methods, $condition);
    }
}