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


use Teebb\CoreBundle\Translation\TranslatableMarkup;

/**
 * EntityTypeMetadata class
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class EntityTypeMetadata implements EntityTypeMetadataInterface
{
    private $label;

    private $type;

    private $description;

    private $controller;

    private $repository;

    private $entity;

    private $service;

    private $formSettings;

    public function __construct(TranslatableMarkup $label, string $type, TranslatableMarkup $description,
                                string $controller, string $repository, string $entity, string $service, array $formSettings)
    {
        $this->label = $label;
        $this->type = $type;
        $this->description = $description;
        $this->controller = $controller;
        $this->repository = $repository;
        $this->entity = $entity;
        $this->service = $service;
        $this->formSettings = $formSettings;
    }

    /**
     * @inheritDoc
     */
    public function getLabel(): TranslatableMarkup
    {
        return $this->label;
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): TranslatableMarkup
    {
        return $this->description;
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
    public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * @inheritDoc
     */
    public function getService(): string
    {
        return $this->service;
    }

    public function getFormSettings(): array
    {
        return $this->formSettings;
    }

}