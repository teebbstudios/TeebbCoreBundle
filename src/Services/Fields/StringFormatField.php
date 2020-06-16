<?php


namespace Teebb\CoreBundle\Services\Fields;

use Teebb\CoreBundle\AbstractService\AbstractField;
use Teebb\CoreBundle\Annotation\FieldType;
use Teebb\CoreBundle\Annotation\Translation;

/**
 * @FieldType(
 *     id="stringFormat",
 *     label=@Translation(message="teebb.core.field.stringFormat.label"),
 *     description=@Translation(message="teebb.core.field.stringFormat.description"),
 *     type="writing",
 *     category=@Translation(message="teebb.core.field.category.text"),
 *     entity="Teebb\CoreBundle\Entity\Fields\SimpleFormatItem"
 * )
 */
class StringFormatField extends AbstractField
{
    
}