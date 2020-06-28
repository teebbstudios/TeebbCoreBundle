<?php


namespace Teebb\CoreBundle\Services\Fields;

use Teebb\CoreBundle\AbstractService\AbstractField;
use Teebb\CoreBundle\Annotation\FieldType;
use Teebb\CoreBundle\Annotation\Translation;

/**
 * @FieldType(
 *     id="textFormatSummary",
 *     label=@Translation(message="teebb.core.field.textFormatSummary.label"),
 *     description=@Translation(message="teebb.core.field.textFormatSummary.description"),
 *     type="writing",
 *     category=@Translation(message="teebb.core.field.category.text"),
 *     entity="Teebb\CoreBundle\Entity\Fields\TextFormatSummaryItem",
 *     formConfigType="Teebb\CoreBundle\Form\Type\FieldConfiguration\TextFormatSummaryItemConfigurationType",
 *     formType="Teebb\CoreBundle\Form\Type\FieldType\TextFormatSummaryFieldType"
 * )
 */
class TextFormatSummaryField extends AbstractField
{
    
}