<?php


namespace Teebb\CoreBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Definition;
use Teebb\CoreBundle\Annotation\EntityType;
use Teebb\CoreBundle\Annotation\FieldType;
use Teebb\CoreBundle\Annotation\FormRow;
use Teebb\CoreBundle\Annotation\Translation;
use Teebb\CoreBundle\Annotation\TypesForm;
use Teebb\CoreBundle\Form\FormRowMarkup;
use Teebb\CoreBundle\Metadata\EntityTypeMetadata;
use Teebb\CoreBundle\Metadata\FieldMetadata;
use Teebb\CoreBundle\Translation\TranslatableMarkup;

trait MetadataTrait
{
    /**
     * @param \ReflectionClass $reflectionClass
     * @param EntityType $annotation
     * @return Definition
     * @throws \Exception
     */
    public function createEntityTypeMetadataDefinition(\ReflectionClass $reflectionClass, EntityType $annotation): Definition
    {
        return new Definition(EntityTypeMetadata::class, [
            $this->createTranslatableMarkupDefinition($annotation->label),
            $annotation->bundle,
            $this->createTranslatableMarkupDefinition($annotation->description),
            $annotation->controller,
            $annotation->repository,
            $annotation->typeEntity,
            $annotation->entity,
            $reflectionClass->getName(),
            $this->createFormSettingsDefinition($annotation->form),
            $annotation->entityFormType
        ]);
    }

    /**
     * @param FieldType $annotation
     * @return Definition
     * @throws \Exception
     */
    public function createFieldTypeMetadataDefinition(FieldType $annotation): Definition
    {
        return new Definition(FieldMetadata::class, [
            $annotation->id,
            $this->createTranslatableMarkupDefinition($annotation->label),
            $this->createTranslatableMarkupDefinition($annotation->description),
            $this->createTranslatableMarkupDefinition($annotation->category),
            $annotation->entity,
            $annotation->formConfigEntity,
            $annotation->formConfigType,
            $annotation->formType
        ]);
    }

    /**
     * @param Translation $translation
     * @return Definition
     */
    public function createTranslatableMarkupDefinition(Translation $translation): Definition
    {
        return new Definition(TranslatableMarkup::class, [
            $translation->message,
            $translation->arguments,
            $translation->domain
        ]);
    }


    public function createFormSettingsDefinition(TypesForm $typesForm): array
    {
        $formRowMarkupDefinitions = [];
        /**@var FormRow $formRow * */
        foreach ($typesForm->formRows as $formRow) {
            $formRowMarkupDefinitions[] = $this->createEntityTypeFormMarkup($formRow);
        }
        return $formRowMarkupDefinitions;
    }

    /**
     * @param FormRow $formRow
     * @return Definition
     */
    public function createEntityTypeFormMarkup(FormRow $formRow): Definition
    {
        return new Definition(FormRowMarkup::class, [
            $formRow->property,
            $formRow->isAlias,
            $formRow->formType,
            $formRow->options
        ]);
    }
}