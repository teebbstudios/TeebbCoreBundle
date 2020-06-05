<?php


namespace Teebb\CoreBundle\Services\Fields;

use Teebb\CoreBundle\AbstractService\AbstractField;
use Teebb\CoreBundle\Annotation\FieldType;
use Teebb\CoreBundle\Annotation\Translation;

/**
 * Integer类型字段
 *
 * @FieldType(
 *     id="integer",
 *     label=@Translation(message="teebb.core.field.integer.label"),
 *     description=@Translation(message="teebb.core.field.integer.description"),
 *     category=@Translation(message="teebb.core.field.category.numeric"),
 *     entity="Teebb\CoreBundle\Entity\Fields\SimpleValueItem"
 * )
 */
class IntegerField extends AbstractField
{
    
}