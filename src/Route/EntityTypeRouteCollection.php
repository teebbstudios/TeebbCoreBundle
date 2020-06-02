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


use Teebb\CoreBundle\Mapping\AnnotationExtractorTrait;
use Teebb\CoreBundle\Metadata\EntityTypeMetadataInterface;
use Symfony\Component\Routing\RouteCollection as BaseRouteCollection;

/**
 * Class EntityTypeRouteCollection, 参考SonataAdminBundle
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class EntityTypeRouteCollection extends BaseRouteCollection implements RouteCollectionInterface
{
    use AnnotationExtractorTrait;

    /**
     * @var EntityTypeMetadataInterface
     */
    private $metadata;

    /**
     * @var RouteFactoryInterface
     */
    private $routeFactory;

    public function __construct(EntityTypeMetadataInterface $metadata)
    {
        $this->metadata = $metadata;
        $this->routeFactory = new RouteFactory();
    }

    /**
     * @inheritDoc
     */
    public function addRoute(string $name, $pattern = null, array $defaults = [],
                             array $requirements = [], array $options = [], $host = '',
                             array $schemes = [], array $methods = [], $condition = ''): void
    {
        $pattern = "/" . $this->metadata->getAlias() . "/" . ($pattern ?: $name);
        $routeName = $this->metadata->getAlias() . '_' . $name;

        $actionCode = $this->getCode($name);

        $controller = $this->metadata->getController();
        if (!isset($defaults['_controller'])) {
            $actionJoiner = false === strpos($controller, '\\') ? ':' : '::';
            if (':' !== $actionJoiner && false !== strpos($controller, ':')) {
                $actionJoiner = ':';
            }
            $defaults['_controller'] = $controller . $actionJoiner . $this->actionify($actionCode);
        }

        $defaults['_teebb_action'] = $name;

        if (!isset($defaults['_teebb_entity_type'])) {
            $defaults['_teebb_entity_type'] = $this->generateServiceId('teebb.core.entity_type.',
                $this->metadata->getService());
        }

        $route = $this->routeFactory->createRoute($pattern, $defaults, $requirements,
            $options, $host, $schemes, $methods, $condition);

        $this->add($routeName, $route);
    }


    /**
     * @param string $name
     *
     * @return string
     */
    public function getCode($name)
    {
        if (false !== strrpos($name, '.')) {
            return $name;
        }

        return $this->metadata->getAlias() . '.' . $name;
    }

    /**
     * Convert a word in to the format for a symfony action action_name => actionName.
     *
     * @param string $action Word to actionify
     *
     * @return string Actionified word
     */
    public function actionify($action)
    {
        if (false !== ($pos = strrpos($action, '.'))) {
            $action = substr($action, $pos + 1);
        }

        // if this is a service rather than just a controller name, the suffix
        // Action is not automatically appended to the method name
        if (false === strpos($this->metadata->getController(), ':')) {
            $action .= 'Action';
        }

        return lcfirst(str_replace(' ', '', ucwords(strtr($action, '_-', '  '))));
    }


}