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

    private $bundle;

    private $description;

    private $controller;

    private $repository;

    private $entity;

    private $service;

    private $formSettings;

    /**
     * @var string
     */
    private $typeEntity;

    /**
     * @var string
     */
    private $entityFormType;

    public function __construct(TranslatableMarkup $label, string $bundle, TranslatableMarkup $description,
                                string $controller, string $repository, string $typeEntity, string $entity,
                                string $service, array $formSettings, string $entityFormType)
    {
        $this->label = $label;
        $this->bundle = $bundle;
        $this->description = $description;
        $this->controller = $controller;
        $this->repository = $repository;
        $this->entity = $entity;
        $this->service = $service;
        $this->formSettings = $formSettings;
        $this->typeEntity = $typeEntity;
        $this->entityFormType = $entityFormType;
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
    public function getBundle(): string
    {
        return $this->bundle;
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

    public function getTypeEntity(): string
    {
        return $this->typeEntity;
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

    public function getEntityFormType(): string
    {
        return $this->entityFormType;
    }

}