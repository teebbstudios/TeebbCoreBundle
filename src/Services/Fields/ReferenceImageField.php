<?php


namespace Teebb\CoreBundle\Services\Fields;

use Teebb\CoreBundle\AbstractService\AbstractField;
use Teebb\CoreBundle\Annotation\FieldType;
use Teebb\CoreBundle\Annotation\Translation;

/**
 * @FieldType(
 *     id="referenceImage",
 *     label=@Translation(message="teebb.core.field.referenceImage.label"),
 *     description=@Translation(message="teebb.core.field.referenceImage.description"),
 *     type="reference",
 *     category=@Translation(message="teebb.core.field.category.reference"),
 *     entity="Teebb\CoreBundle\Entity\Fields\ReferenceImageItem",
 *     formConfigType="Teebb\CoreBundle\Form\Type\FieldConfiguration\ReferenceImageItemConfigurationType",
 *     formConfigEntity="Teebb\CoreBundle\Entity\Fields\Configuration\ReferenceImageItemConfiguration",
 *     formType="Teebb\CoreBundle\Form\Type\FieldType\ReferenceImageFieldType"
 * )
 */
class ReferenceImageField extends AbstractField
{
    
}