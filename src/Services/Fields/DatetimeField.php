<?php


namespace Teebb\CoreBundle\Services\Fields;

use Teebb\CoreBundle\AbstractService\AbstractField;
use Teebb\CoreBundle\Annotation\FieldType;
use Teebb\CoreBundle\Annotation\Translation;

/**
 * Datetime类型字段
 *
 * @FieldType(
 *     id="datetime",
 *     label=@Translation(message="teebb.core.field.datetime.label"),
 *     description=@Translation(message="teebb.core.field.datetime.description"),
 *     type="simple",
 *     category=@Translation(message="teebb.core.field.category.simple"),
 *     entity="Teebb\CoreBundle\Entity\Fields\DatetimeItem",
 *     formConfigType="Teebb\CoreBundle\Form\Type\FieldConfiguration\DatetimeItemConfigurationType",
 *     formConfigEntity="Teebb\CoreBundle\Entity\Fields\Configuration\DatetimeItemConfiguration",
 *     formType="Teebb\CoreBundle\Form\Type\FieldType\DatetimeFieldType"
 * )
 */
class DatetimeField extends AbstractField
{
    
}