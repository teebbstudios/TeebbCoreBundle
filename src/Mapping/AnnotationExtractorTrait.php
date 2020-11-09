<?php
/**
 * This file is part of the TeebbCoreBundle package.
 *
 * Author: Quan Weiwei <qww.zone@gmail.com>
 * Date: 2020/5/28
 * (c) teebbstudios
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Teebb\CoreBundle\Mapping;


use Doctrine\Common\Annotations\Reader;
use Doctrine\Inflector\InflectorFactory;

trait AnnotationExtractorTrait
{
    public function readAnnotation(\ReflectionClass $reflectionClass, Reader $reader, string $annotationName)
    {
        $annotation = $reader->getClassAnnotation($reflectionClass, $annotationName);

        yield $reflectionClass => $annotation;
    }

    /**
     * Generates a unique, per-class and per-filter identifier prefixed by `teebb.core.entity_type.{classname}`
     *
     * @param string $prefix
     * @param string $serviceName
     * @return string
     */
    private function generateServiceId(string $prefix, string $serviceName): string
    {
        return $prefix . InflectorFactory::create()->build()->tableize(substr($serviceName, strripos($serviceName, '\\')));
    }
}