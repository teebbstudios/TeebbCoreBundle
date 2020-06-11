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
 * Interface RouteBuilderInterface
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
interface RouteBuilderInterface
{
    public function build(RouteCollectionInterface $routeCollection): void;
}