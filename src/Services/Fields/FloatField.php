<?php


namespace Teebb\CoreBundle\Services\Fields;

use Teebb\CoreBundle\AbstractService\AbstractField;
use Teebb\CoreBundle\Annotation\FieldType;
use Teebb\CoreBundle\Annotation\Translation;

/**
 * Float类型字段
 *
 * @FieldType(
 *     id="float",
 *     label=@Translation(message="teebb.core.field.float.label"),
 *     description=@Translation(message="teebb.core.field.float.description"),
 *     type="numeric",
 *     category=@Translation(message="teebb.core.field.category.numeric"),
 *     entity="Teebb\CoreBundle\Entity\Fields\SimpleValueItem"
 * )
 */
class FloatField extends AbstractField
{
    
}