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

/**
 * 读取Annotation注解设置
 *
 * @deprecated
 */
class AnnotationParser
{
    /**
     * @var CachedReader
     */
    private $reader;

    /**
     * @var array
     */
    private $directories;

    public function __construct(CachedReader $reader, array $directories)
    {
        $this->reader = $reader;
        $this->directories = $directories;
    }

    /**
     * 读取类注释中指定的Annotation
     *
     * @param string $annotationName
     * @return array 返回
     */
    public function readAllSpecifiedTypeAnnotation(string $annotationName): array
    {
        $allAnnotations = [];
        foreach (ReflectionClassRecursiveIterator::getReflectionClassesFromDirectories($this->directories)
                 as $className => $reflectionClass) {
            $annotation = $this->reader->getClassAnnotation($reflectionClass, $annotationName);

            if($annotation instanceof $annotationName){
                $allAnnotations[$className] = $annotation;
            }
        }

        return $allAnnotations;
    }

}