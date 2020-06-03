<?php
/**
 * This file is part of the TeebbCoreBundle package.
 *
 * Author: Quan Weiwei <qww.zone@gmail.com>
 * Date: 2020/5/20
 * (c) teebbstudios
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Teebb\CoreBundle\Tests\Entity;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Teebb\CoreBundle\Entity\Types\ContentType;
use Teebb\CoreBundle\Metadata\EntityTypeMetadata;
use Teebb\CoreBundle\Route\EntityTypeActions;
use Teebb\CoreBundle\Route\EntityTypeRouteCollection;

class ContentTypeTest extends KernelTestCase
{
    /**
     * 测试ContentType类
     */
    public function testContentType()
    {
        $kernel = self::bootKernel();

        $container = $kernel->getContainer();

        $contentTypeService = $container->get('teebb.core.entity_type.content_entity_type');
        $contentTypeMetadata = $contentTypeService->getEntityTypeMetadata();

        $this->assertNotNull($contentTypeService);
        $this->assertInstanceOf(EntityTypeMetadata::class, $contentTypeMetadata);
        $this->assertSame(ContentType::class, $contentTypeMetadata->getEntity());
    }

    /**
     * 测试ContentType类生成Route
     */
    public function testContentTypeRoute()
    {
        $kernel = self::bootKernel();

        $container = $kernel->getContainer();

        $contentTypeService = $container->get('teebb.core.entity_type.content_entity_type');

        $metadata = $contentTypeService->getEntityTypeMetadata();

        $routeCollection = new EntityTypeRouteCollection($metadata);

        $pathBuilder = $contentTypeService->getPathBuilder();

        $pathBuilder->build($routeCollection);


        $indexRoute = $routeCollection->get($metadata->getAlias() . '_' . EntityTypeActions::INDEX);
        $this->assertNotNull($indexRoute);
        $this->assertSame('/types/index', $indexRoute->getPath());

        $routes = $contentTypeService->getRoutes();

        $this->assertInstanceOf(EntityTypeRouteCollection::class, $routes);
        $this->assertArrayHasKey('types_index', $routes->all());

    }

}