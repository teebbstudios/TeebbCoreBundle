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
 *     type="numeric",
 *     category=@Translation(message="teebb.core.field.category.numeric"),
 *     entity="Teebb\CoreBundle\Entity\Fields\IntegerItem",
 *     formConfigType="Teebb\CoreBundle\Form\Type\FieldConfiguration\IntegerItemConfigurationType",
 *     formConfigEntity="Teebb\CoreBundle\Entity\Fields\Configuration\IntegerItemConfiguration",
 *     formType="Teebb\CoreBundle\Form\Type\FieldType\IntegerFieldType"
 * )
 */
class IntegerField extends AbstractField
{
    
}