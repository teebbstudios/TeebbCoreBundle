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

use Symfony\Component\Config\Loader\Loader;

/**
 * 内容实体类型的RouteLoader添加对应routes到系统
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class EntityTypeRouteLoader extends Loader
{
    
    private $isLoaded = false;

    /**
     * 所有EntityType类型Service id集合
     *
     * @var array
     */
    private $entityTypeObjects;

    public function __construct(array $entityTypeObjects)
    {
        $this->entityTypeObjects = $entityTypeObjects;
    }

    public function load($resource, string $type = null)
    {
        // TODO: Implement load() method.
    }

    public function supports($resource, string $type = null)
    {
        // TODO: Implement supports() method.
    }
}