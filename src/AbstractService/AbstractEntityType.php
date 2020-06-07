<?php
/**
 * This file is part of the TeebbCoreBundle package.
 *
 * Author: Quan Weiwei <qww.zone@gmail.com>
 * Date: 2020/5/21
 * (c) teebbstudios
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Teebb\CoreBundle\AbstractService;

use Teebb\CoreBundle\Metadata\EntityTypeMetadataInterface;
use Teebb\CoreBundle\Repository\RepositoryInterface;
use Teebb\CoreBundle\Route\EntityTypePathBuilder;
use Teebb\CoreBundle\Route\EntityTypeRouteCollection;

/**
 * 内容实体类型类
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
abstract class AbstractEntityType implements EntityTypeInterface
{
    /**
     * @var EntityTypeMetadataInterface
     */
    private $metadata;

    /**
     * 内容实体类型的routes
     *
     * @var EntityTypeRouteCollection
     */
    private $routes;

    /**
     * @var EntityTypePathBuilder
     */
    private $pathBuilder;

    /**
     * @var RepositoryInterface
     */
    private $entityTypeRepository;

    public function __construct(EntityTypePathBuilder $pathBuilder)
    {
        $this->pathBuilder = $pathBuilder;
    }

    /**
     * @inheritDoc
     */
    public function setEntityTypeMetadata(EntityTypeMetadataInterface $metadata): void
    {
        $this->metadata = $metadata;
    }

    /**
     * @inheritDoc
     */
    public function getEntityTypeMetadata(): EntityTypeMetadataInterface
    {
        return $this->metadata;
    }

    /**
     * @inheritDoc
     */
    public function setRepository(RepositoryInterface $repository): void
    {
        $this->entityTypeRepository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function getRepository(): RepositoryInterface
    {
        return $this->entityTypeRepository;
    }

    /**
     * @inheritDoc
     */
    public function getRoutes(): EntityTypeRouteCollection
    {

        $this->buildRoutes();

        return $this->routes;
    }

    /**
     * @inheritDoc
     */
    public function buildRoutes(): void
    {
        if (null !== $this->routes) {
            return;
        }

        $this->routes = new EntityTypeRouteCollection($this->metadata);

        $this->pathBuilder->build($this->routes);

        $this->configureRoutes($this->routes);
    }

    /**
     * @return EntityTypePathBuilder
     */
    public function getPathBuilder(): EntityTypePathBuilder
    {
        return $this->pathBuilder;
    }

    /**
     * 覆盖此方法以自定义路由添加到EntityTypeRouteCollection.
     *
     * 自定义Controller继承自AbstractEntityTypeController并实现Action，并在注释中配置对应controller。
     *
     * @param EntityTypeRouteCollection $routeCollection
     */
    protected function configureRoutes(EntityTypeRouteCollection $routeCollection): void
    {
        //$this->routing->addRoute('example', 'pattern');
    }


    public function getFields()
    {
        // TODO: Implement getFields() method.
    }

    public function addField(FieldInterface $field): void
    {
        // TODO: Implement addField() method.
    }

}