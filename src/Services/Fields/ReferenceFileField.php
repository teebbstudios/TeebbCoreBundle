<?php


namespace Teebb\CoreBundle\Services\Fields;

use Teebb\CoreBundle\AbstractService\AbstractField;
use Teebb\CoreBundle\Annotation\FieldType;
use Teebb\CoreBundle\Annotation\Translation;

/**
 * @FieldType(
 *     id="referenceFile",
 *     label=@Translation(message="teebb.core.field.referenceFile.label"),
 *     description=@Translation(message="teebb.core.field.referenceFile.description"),
 *     category=@Translation(message="teebb.core.field.category.reference"),
 *     entity="Teebb\CoreBundle\Entity\Fields\ReferenceFileItem"
 * )
 */
class ReferenceFileField extends AbstractField
{
    
}