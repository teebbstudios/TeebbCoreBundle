<?php


namespace Teebb\CoreBundle\Services\Fields;

use Teebb\CoreBundle\AbstractService\AbstractField;
use Teebb\CoreBundle\Annotation\FieldType;
use Teebb\CoreBundle\Annotation\Translation;

/**
 * Decimal类型字段
 *
 * @FieldType(
 *     id="decimal",
 *     label=@Translation(message="teebb.core.field.decimal.label"),
 *     description=@Translation(message="teebb.core.field.decimal.description"),
 *     category=@Translation(message="teebb.core.field.category.numeric"),
 *     entity="Teebb\CoreBundle\Entity\Fields\SimpleValueItem"
 * )
 */
class DecimalField extends AbstractField
{
    
}