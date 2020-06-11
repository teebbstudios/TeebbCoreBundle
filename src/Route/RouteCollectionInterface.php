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

/**
 * Interface RouteCollectionInterface
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
interface RouteCollectionInterface
{
    /**
     * 创建Route并添加到RouteCollection
     *
     * @param string $name
     * @param null $pattern
     * @param array $defaults
     * @param array $requirements
     * @param array $options
     * @param string $host
     * @param array $schemes
     * @param array $methods
     * @param string $condition
     */
    public function addRoute(string $name, $pattern = null, array $defaults = [],
                             array $requirements = [], array $options = [], $host = '',
                             array $schemes = [], array $methods = [], $condition = ''): void;
}