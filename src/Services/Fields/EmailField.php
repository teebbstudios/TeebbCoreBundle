<?php


namespace Teebb\CoreBundle\Services\Fields;

use Teebb\CoreBundle\AbstractService\AbstractField;
use Teebb\CoreBundle\Annotation\FieldType;
use Teebb\CoreBundle\Annotation\Translation;

/**
 * Email类型字段
 *
 * @FieldType(
 *     id="email",
 *     label=@Translation(message="teebb.core.field.email.label"),
 *     description=@Translation(message="teebb.core.field.email.description"),
 *     type="simple",
 *     category=@Translation(message="teebb.core.field.category.simple"),
 *     entity="Teebb\CoreBundle\Entity\Fields\EmailItem",
 *     formConfigType="Teebb\CoreBundle\Form\Type\FieldConfiguration\EmailItemConfigurationType",
 *     formConfigEntity="Teebb\CoreBundle\Entity\Fields\Configuration\EmailItemConfiguration",
 *     formType="Teebb\CoreBundle\Form\Type\FieldType\EmailFieldType"
 * )
 */
class EmailField extends AbstractField
{
    
}