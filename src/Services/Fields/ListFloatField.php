<?php


namespace Teebb\CoreBundle\Services\Fields;

use Teebb\CoreBundle\AbstractService\AbstractField;
use Teebb\CoreBundle\Annotation\FieldType;
use Teebb\CoreBundle\Annotation\Translation;

/**
 * ListFloat类型字段
 *
 * @FieldType(
 *     id="listFloat",
 *     label=@Translation(message="teebb.core.field.listFloat.label"),
 *     description=@Translation(message="teebb.core.field.listFloat.description"),
 *     type="numeric",
 *     category=@Translation(message="teebb.core.field.category.numeric"),
 *     entity="Teebb\CoreBundle\Entity\Fields\SimpleValueItem"
 * )
 */
class ListFloatField extends AbstractField
{
    
}