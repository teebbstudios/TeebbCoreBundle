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
use Teebb\CoreBundle\Route\EntityTypePathBuilder;
use Teebb\CoreBundle\Route\EntityTypeRouteCollection;

/**
 * 内容实体类型类
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class AbstractEntityType implements EntityTypeInterface
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

    }

    public function getFields()
    {
        // TODO: Implement getFields() method.
    }

    public function addField(FieldTypeInterface $field): void
    {
        // TODO: Implement addField() method.
    }


}