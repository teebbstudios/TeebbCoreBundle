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
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouteCollection;
use Teebb\CoreBundle\AbstractService\AbstractEntityType;

/**
 * 内容实体类型的RouteLoader
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class EntityTypeRouteLoader extends Loader
{
    public const ROUTE_TYPE_NAME = 'entity_type';

    private $isLoaded = false;

    /**
     * 所有EntityType类型Service集合
     *
     * @var array
     */
    private $entityTypeServices;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(array $entityTypeServices, ContainerInterface $container)
    {
        $this->entityTypeServices = $entityTypeServices;
        $this->container = $container;
    }

    public function load($resource, string $type = null)
    {
        if (true === $this->isLoaded) {
            throw new \RuntimeException('Do not add the "entity_type" loader twice');
        }

        $routeCollection = new RouteCollection();

        foreach ($this->entityTypeServices as $entityTypeServiceId => $tags) {

            /**
             * @var AbstractEntityType $entityTypeService
             */
            $entityTypeService = $this->container->get($entityTypeServiceId);

            $routes = $entityTypeService->getRoutes();

            $reflection = new \ReflectionObject($routes);
            if (file_exists($reflection->getFileName())) {
                $routes->addResource(new FileResource($reflection->getFileName()));
            }

            $routeCollection->addCollection($routes);
        }

        $reflection = new \ReflectionObject($this->container);

        if (file_exists($reflection->getFileName())) {
            $routeCollection->addResource(new FileResource($reflection->getFileName()));
        }

        $this->isLoaded = true;

        return $routeCollection;
    }

    public function supports($resource, string $type = null)
    {
        return self::ROUTE_TYPE_NAME === $type;
    }
}