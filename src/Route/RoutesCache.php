<?php


namespace Teebb\CoreBundle\Route;


use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RoutesCache
{
    /**
     * @var string
     */
    protected $cacheFolder;

    /**
     * @var bool
     */
    protected $debug;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param string $cacheFolder
     * @param bool $debug
     * @param ContainerInterface $container
     */
    public function __construct($cacheFolder, $debug, ContainerInterface $container)
    {
        $this->cacheFolder = $cacheFolder;
        $this->debug = $debug;
        $this->container = $container;
    }

    /**
     * @param string $serviceId
     * @return mixed
     */
    public function load(string $serviceId)
    {
        $filename = $this->cacheFolder . '/route_' . md5($serviceId);

        $cache = new ConfigCache($filename, $this->debug);

        if (!$cache->isFresh()) {
            $resources = [];
            $routes = [];

            $service = $this->container->get($serviceId);
            if (!$service) {
                throw new \RuntimeException(sprintf('The service "%s" does not exists', $serviceId));
            }

            $reflection = new \ReflectionObject($service);
            if (file_exists($reflection->getFileName())) {
                $resources[] = new FileResource($reflection->getFileName());
            }

            if (!$service->getRoutes()) {
                throw new \RuntimeException(sprintf('Invalid data type, service %s::getRoutes must return a RouteCollection', $serviceId));
            }

            $routes[$serviceId] = $service->getRoutes()->all();

            $cache->write(serialize($routes), $resources);
        }

        return unserialize(file_get_contents($filename));
    }
}