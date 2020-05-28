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

    private $entityTypeClassName;


    public function __construct(string $name, string $alias, string $description,
                                string $entityTypeClassName,
                                string $controller, string $repository)
    {
        $this->name = $name;
        $this->alias = $alias;
        $this->description = $description;
        $this->controller = $controller;
        $this->repository = $repository;
        $this->entityTypeClassName = $entityTypeClassName;
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
    public function getEntityTypeClassName(): string
    {
        return $this->entityTypeClassName;
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

}