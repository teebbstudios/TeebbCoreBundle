<?php
/**
 * This file is part of the TeebbCoreBundle package.
 *
 * Author: Quan Weiwei <qww.zone@gmail.com>
 * Date: 2020/5/26
 * (c) teebbstudios
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Teebb\CoreBundle\Metadata;

/**
 * EntityTypeMetadata class
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class EntityTypeMetadata implements EntityTypeMetadataInterface
{
    private $name;

    private $alias;

    private $description;

    private $controller;

    private $repository;

    private $entityClassName;

    private $service;

    public function __construct(string $name, string $alias, string $description,
                                string $entityClassName, string $controller, string $repository, string $service)
    {
        $this->name = $name;
        $this->alias = $alias;
        $this->description = $description;
        $this->controller = $controller;
        $this->repository = $repository;
        $this->entityClassName = $entityClassName;
        $this->service = $service;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @inheritDoc
     */
    public function getEntityClassName(): string
    {
        return $this->entityClassName;
    }

    /**
     * @inheritDoc
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @inheritDoc
     */
    public function getRepository(): string
    {
        return $this->repository;
    }

    /**
     * @inheritDoc
     */
    public function getService(): string
    {
        return $this->service;
    }


}