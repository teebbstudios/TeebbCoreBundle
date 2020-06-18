<?php


namespace Teebb\CoreBundle\Metadata;


use Teebb\CoreBundle\Translation\TranslatableMarkup;

class FieldMetadata implements FieldMetadataInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var TranslatableMarkup
     */
    private $label;

    /**
     * @var TranslatableMarkup
     */
    private $description;

    /**
     * @var TranslatableMarkup
     */
    private $category;

    /**
     * @var string
     */
    private $entity;

    /**
     * 字段配置表单Type全类名
     * @var string
     */
    private $formConfigType;

    public function __construct(string $id, TranslatableMarkup $label, TranslatableMarkup $description,
                                TranslatableMarkup $category, string $entity, string $formConfigType)
    {
        $this->id = $id;
        $this->label = $label;
        $this->description = $description;
        $this->category = $category;
        $this->entity = $entity;
        $this->formConfigType = $formConfigType;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLabel(): TranslatableMarkup
    {
        return $this->label;
    }

    public function getDescription(): TranslatableMarkup
    {
        return $this->description;
    }

    public function getCategory(): TranslatableMarkup
    {
        return $this->category;
    }

    public function getEntity(): string
    {
        return $this->entity;
    }

    public function getFieldConfigType(): string
    {
        return $this->formConfigType;
    }


}