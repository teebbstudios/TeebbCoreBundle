<?php


namespace Teebb\CoreBundle\Services\Fields;

use Teebb\CoreBundle\AbstractService\AbstractField;
use Teebb\CoreBundle\Annotation\FieldType;
use Teebb\CoreBundle\Annotation\Translation;

/**
 * ListInteger类型字段
 *
 * @FieldType(
 *     id="referenceContent",
 *     label=@Translation(message="teebb.core.field.referenceContent.label"),
 *     description=@Translation(message="teebb.core.field.referenceContent.description"),
 *     type="reference",
 *     category=@Translation(message="teebb.core.field.category.reference"),
 *     entity="Teebb\CoreBundle\Entity\Fields\ReferenceEntityItem",
 *     formConfigType="Teebb\CoreBundle\Form\Type\FieldConfiguration\ReferenceContentItemConfigurationType",
 *     formConfigEntity="Teebb\CoreBundle\Entity\Fields\Configuration\ReferenceContentItemConfiguration",
 *     formType="Teebb\CoreBundle\Form\Type\FieldType\ReferenceContentFieldType"
 * )
 */
class ReferenceContentField extends AbstractField
{
    
}