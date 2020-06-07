<?php


namespace Teebb\CoreBundle\Services\Fields;

use Teebb\CoreBundle\AbstractService\AbstractField;
use Teebb\CoreBundle\Annotation\FieldType;
use Teebb\CoreBundle\Annotation\Translation;

/**
 * @FieldType(
 *     id="text",
 *     label=@Translation(message="teebb.core.field.text.label"),
 *     description=@Translation(message="teebb.core.field.text.description"),
 *     type="text",
 *     category=@Translation(message="teebb.core.field.category.text"),
 *     entity="Teebb\CoreBundle\Entity\Fields\SimpleValueItem"
 * )
 */
class TextField extends AbstractField
{
    
}