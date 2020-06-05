<?php


namespace Teebb\CoreBundle\Services\Fields;

use Teebb\CoreBundle\AbstractService\AbstractField;
use Teebb\CoreBundle\Annotation\FieldType;
use Teebb\CoreBundle\Annotation\Translation;

/**
 * ListInteger类型字段
 *
 * @FieldType(
 *     id="listInteger",
 *     label=@Translation(message="teebb.core.field.listInteger.label"),
 *     description=@Translation(message="teebb.core.field.listInteger.description"),
 *     category=@Translation(message="teebb.core.field.category.numeric"),
 *     entity="Teebb\CoreBundle\Entity\Fields\SimpleValueItem"
 * )
 */
class ListIntegerField extends AbstractField
{
    
}