<?php


namespace Teebb\CoreBundle\Services\Fields;

use Teebb\CoreBundle\AbstractService\AbstractField;
use Teebb\CoreBundle\Annotation\FieldType;
use Teebb\CoreBundle\Annotation\Translation;

/**
 * @FieldType(
 *     id="textFormat",
 *     label=@Translation(message="teebb.core.field.textFormat.label"),
 *     description=@Translation(message="teebb.core.field.textFormat.description"),
 *     type="writing",
 *     category=@Translation(message="teebb.core.field.category.text"),
 *     entity="Teebb\CoreBundle\Entity\Fields\TextFormatItem",
 *     formConfigType="Teebb\CoreBundle\Form\Type\FieldConfiguration\TextFormatItemConfigurationType",
 *     formConfigEntity="Teebb\CoreBundle\Entity\Fields\Configuration\TextFormatItemConfiguration",
 *     formType="Teebb\CoreBundle\Form\Type\FieldType\TextFormatFieldType"
 * )
 */
class TextFormatField extends AbstractField
{
    
}