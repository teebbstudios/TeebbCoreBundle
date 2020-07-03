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
 *     type="numeric",
 *     category=@Translation(message="teebb.core.field.category.numeric"),
 *     entity="Teebb\CoreBundle\Entity\Fields\SimpleValueItem",
 *     formConfigType="Teebb\CoreBundle\Form\Type\FieldConfiguration\DecimalItemConfigurationType",
 *     formConfigEntity="Teebb\CoreBundle\Entity\Fields\Configuration\DecimalItemConfiguration",
 *     formType="Teebb\CoreBundle\Form\Type\FieldType\DecimalFieldType"
 * )
 */
class DecimalField extends AbstractField
{
    
}