<?php


namespace Teebb\CoreBundle\Route;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * interface PathInfoGeneratorInterface
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
interface PathInfoGeneratorInterface
{
    /**
     * 生成url
     * @param $name
     * @param array $parameters
     * @param int $referenceType
     * @return string
     */
    public function generate($name, array $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string;

    /**
     * @param string $serviceId
     * @param string $routeName
     * @return bool
     */
    public function hasRoute(string $serviceId, string $routeName): bool;

}