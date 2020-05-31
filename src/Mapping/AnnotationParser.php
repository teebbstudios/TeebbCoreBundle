<?php
/**
 * This file is part of the TeebbCoreBundle package.
 *
 * Author: Quan Weiwei <qww.zone@gmail.com>
 * Date: 2020/5/22
 * (c) teebbstudios
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Teebb\CoreBundle\Mapping;

use Doctrine\Common\Annotations\CachedReader;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * 读取Annotation注解设置
 */
class AnnotationParser
{
    /**
     * @var CachedReader
     */
    private $reader;

    public function __construct(CachedReader $reader, ContainerInterface $container)
    {
        $this->reader = $reader;
    }

    /**
     * 读取类注释中指定的Annotation
     *
     * @param string $annotationName
     * @param array $directories
     * @return array
     */
    public function readAllSpecifiedTypeAnnotation(string $annotationName, array $directories): array
    {
        $allAnnotations = [];
        foreach (ReflectionClassRecursiveIterator::getReflectionClassesFromDirectories($directories)
                 as $className => $reflectionClass) {
            $annotation = $this->reader->getClassAnnotation($reflectionClass, $annotationName);

            if($annotation instanceof $annotationName){
                $allAnnotations[$className] = $annotation;
            }
        }

        return $allAnnotations;
    }

}