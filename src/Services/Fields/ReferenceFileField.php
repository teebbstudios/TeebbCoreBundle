<?php


namespace Teebb\CoreBundle\Services\Fields;

use Teebb\CoreBundle\AbstractService\AbstractField;
use Teebb\CoreBundle\Annotation\FieldType;
use Teebb\CoreBundle\Annotation\Translation;

/**
 * @FieldType(
 *     id="referenceFile",
 *     label=@Translation(message="teebb.core.field.referenceFile.label"),
 *     description=@Translation(message="teebb.core.field.referenceFile.description"),
 *     type="reference",
 *     category=@Translation(message="teebb.core.field.category.reference"),
 *     entity="Teebb\CoreBundle\Entity\Fields\ReferenceFileItem",
 *     formConfigType="Teebb\CoreBundle\Form\Type\FieldConfiguration\ReferenceFileItemConfigurationType",
 *     formConfigEntity="Teebb\CoreBundle\Entity\Fields\Configuration\ReferenceFileItemConfiguration",
 *     formType="Teebb\CoreBundle\Form\Type\FieldType\ReferenceFileFieldType"
 * )
 */
class ReferenceFileField extends AbstractField
{
    
}