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

interface RouteFactoryInterface
{
    /**
     * 创建Route
     *
     * @param string $path
     * @param array $defaults
     * @param array $requirements
     * @param array $options
     * @param string $host
     * @param array $schemes
     * @param array $methods
     * @param string $condition
     * @return Route
     */
    public function createRoute(string $path, array $defaults = [], array $requirements = [],
                                array $options = [], string $host = '', array $schemes = [],
                                array $methods = [], string $condition = ''): Route;


}