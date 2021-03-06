<?php


namespace Teebb\CoreBundle\Services\Fields;

use Teebb\CoreBundle\AbstractService\AbstractField;
use Teebb\CoreBundle\Annotation\FieldType;
use Teebb\CoreBundle\Annotation\Translation;

/**
 * @FieldType(
 *     id="referenceUser",
 *     label=@Translation(message="teebb.core.field.referenceUser.label"),
 *     description=@Translation(message="teebb.core.field.referenceUser.description"),
 *     type="reference",
 *     category=@Translation(message="teebb.core.field.category.reference"),
 *     entity="Teebb\CoreBundle\Entity\Fields\ReferenceUserItem",
 *     formConfigType="Teebb\CoreBundle\Form\Type\FieldConfiguration\ReferenceUserItemConfigurationType",
 *     formConfigEntity="Teebb\CoreBundle\Entity\Fields\Configuration\ReferenceUserItemConfiguration",
 *     formType="Teebb\CoreBundle\Form\Type\FieldType\ReferenceUserFieldType"
 * )
 */
class ReferenceUserField extends AbstractField
{
    
}