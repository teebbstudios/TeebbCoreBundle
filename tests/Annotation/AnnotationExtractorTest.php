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

namespace Teebb\CoreBundle\Tests\Annotation;


use Teebb\CoreBundle\Test\TeebbCoreTest;

class AnnotationExtractorTest extends TeebbCoreTest
{
    /**
     * 测试Annotation Mapping路径
     */
    public function testAnnotationMappingPath()
    {
        $kernel = self::bootKernel();

        $container = $kernel->getContainer();

        $directories = $container->getParameter('teebb.core.mapping.directories');

        $this->assertNotFalse($directories);
        $this->assertIsArray($directories);
    }


}