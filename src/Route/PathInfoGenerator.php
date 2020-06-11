<?php


namespace Teebb\CoreBundle\Route;


use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class PathInfoGenerator implements PathInfoGeneratorInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var RoutesCache
     */
    private $routesCache;

    /**
     * @var array
     */
    private $cache = [];

    public function __construct(RouterInterface $router, RoutesCache $routesCache)
    {
        $this->router = $router;
        $this->routesCache = $routesCache;
    }

    /**
     * @inheritDoc
     */
    public function generate($name, array $parameters = [],
                             $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        return $this->router->generate($name, $parameters, $referenceType);
    }

    /**
     * @inheritDoc
     */
    public function hasRoute(string $serviceId, string $routeName): bool
    {
        $this->cache = $this->routesCache->load($serviceId);

        return array_key_exists($routeName, $this->cache[$serviceId]);
    }

}