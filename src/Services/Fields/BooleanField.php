<?php


namespace Teebb\CoreBundle\Services\Fields;

use Teebb\CoreBundle\AbstractService\AbstractField;
use Teebb\CoreBundle\Annotation\FieldType;
use Teebb\CoreBundle\Annotation\Translation;

/**
 * Boolean类型字段
 *
 * @FieldType(
 *     id="boolean",
 *     label=@Translation(message="teebb.core.field.boolean.label"),
 *     description=@Translation(message="teebb.core.field.boolean.description"),
 *     type="simple",
 *     category=@Translation(message="teebb.core.field.category.simple"),
 *     entity="Teebb\CoreBundle\Entity\Fields\SimpleValueItem",
 *     formConfigType="Teebb\CoreBundle\Form\Type\FieldConfiguration\BooleanItemConfigurationType",
 *     formConfigEntity="Teebb\CoreBundle\Entity\Fields\Configuration\BooleanItemConfiguration",
 *     formType="Teebb\CoreBundle\Form\Type\FieldType\BooleanFieldType"
 * )
 */
class BooleanField extends AbstractField
{
    
}