<?php


namespace Teebb\CoreBundle\Services\Fields;

use Teebb\CoreBundle\AbstractService\AbstractField;
use Teebb\CoreBundle\Annotation\FieldType;
use Teebb\CoreBundle\Annotation\Translation;

/**
 * @FieldType(
 *     id="referenceTaxonomy",
 *     label=@Translation(message="teebb.core.field.referenceTaxonomy.label"),
 *     description=@Translation(message="teebb.core.field.referenceTaxonomy.description"),
 *     type="reference",
 *     category=@Translation(message="teebb.core.field.category.reference"),
 *     entity="Teebb\CoreBundle\Entity\Fields\ReferenceEntityItem",
 *     formConfigType="Teebb\CoreBundle\Form\Type\FieldConfiguration\ReferenceTaxonomyItemConfigurationType"
 * )
 */
class ReferenceTaxonomyField extends AbstractField
{
    
}