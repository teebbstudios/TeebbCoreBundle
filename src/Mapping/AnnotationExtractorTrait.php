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

trait AnnotationExtractorTrait
{
    public function readAnnotation(\ReflectionClass $reflectionClass, Reader $reader, string $annotationName)
    {
        $annotation = $reader->getClassAnnotation($reflectionClass, $annotationName);

        yield $reflectionClass => $annotation;
    }

    
}