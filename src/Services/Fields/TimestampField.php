<?php


namespace Teebb\CoreBundle\Services\Fields;

use Teebb\CoreBundle\AbstractService\AbstractField;
use Teebb\CoreBundle\Annotation\FieldType;
use Teebb\CoreBundle\Annotation\Translation;

/**
 * Timestamp类型字段
 *
 * @FieldType(
 *     id="timestamp",
 *     label=@Translation(message="teebb.core.field.timestamp.label"),
 *     description=@Translation(message="teebb.core.field.timestamp.description"),
 *     type="simple",
 *     category=@Translation(message="teebb.core.field.category.simple"),
 *     entity="Teebb\CoreBundle\Entity\Fields\SimpleValueItem",
 *     formConfigType="Teebb\CoreBundle\Form\Type\FieldConfiguration\TimestampItemConfigurationType"
 * )
 */
class TimestampField extends AbstractField
{

}