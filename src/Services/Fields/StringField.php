<?php


namespace Teebb\CoreBundle\Services\Fields;

use Teebb\CoreBundle\AbstractService\AbstractField;
use Teebb\CoreBundle\Annotation\FieldType;
use Teebb\CoreBundle\Annotation\Translation;

/**
 * @FieldType(
 *     id="string",
 *     label=@Translation(message="teebb.core.field.string.label"),
 *     description=@Translation(message="teebb.core.field.string.description"),
 *     type="writing",
 *     category=@Translation(message="teebb.core.field.category.text"),
 *     entity="Teebb\CoreBundle\Entity\Fields\SimpleValueItem",
 *     formConfigType="Teebb\CoreBundle\Form\Type\FieldConfiguration\StringItemConfigurationType",
 *     formConfigEntity="Teebb\CoreBundle\Entity\Fields\Configuration\StringItemConfiguration",
 *     formType="Teebb\CoreBundle\Form\Type\FieldType\StringFieldType"
 * )
 */
class StringField extends AbstractField
{
    
}