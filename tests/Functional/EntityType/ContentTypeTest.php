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

namespace Teebb\CoreBundle\Tests\Functional\EntityType;


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

        $contentTypeService = $container->get('teebb.core.entity_type.types_entity_type');
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

        $contentTypeService = $container->get('teebb.core.entity_type.types_entity_type');

        $metadata = $contentTypeService->getEntityTypeMetadata();

        $routeCollection = new EntityTypeRouteCollection($metadata);

        $pathBuilder = $contentTypeService->getPathBuilder();

        $pathBuilder->build($routeCollection);


        $indexRoute = $routeCollection->get($metadata->getType() . '_' . EntityTypeActions::INDEX);
        $this->assertNotNull($indexRoute);
        $this->assertSame('/types/index', $indexRoute->getPath());

        $routes = $contentTypeService->getRoutes();

        $this->assertInstanceOf(EntityTypeRouteCollection::class, $routes);
        $this->assertArrayHasKey('types_index', $routes->all());

    }

    public function testGenerateFieldListInfo()
    {
        $kernel = self::bootKernel();

        $container = $kernel->getContainer();

        $contentTypeService = $container->get('teebb.core.entity_type.types_entity_type');

        $result = $contentTypeService->generateFieldListData();

        $this->assertArrayHasKey('text', $result);
        $this->assertArrayHasKey('numeric', $result);
        $this->assertArrayHasKey('reference', $result);
        $this->assertArrayHasKey('simple', $result);
        $this->assertSame(5, sizeof($result['text']));
        $this->assertSame(5, sizeof($result['numeric']));
        $this->assertSame(5, sizeof($result['reference']));
        $this->assertSame(4, sizeof($result['simple']));
    }

    public function testRouteCache()
    {
        $kernel = self::bootKernel();

        $container = $kernel->getContainer();

        $routeCache = $container->get('teebb.core.route.cache');

        $cache = $routeCache->load('teebb.core.entity_type.types_entity_type');

        $this->assertArrayHasKey('teebb.core.entity_type.types_entity_type', $cache);
    }
}