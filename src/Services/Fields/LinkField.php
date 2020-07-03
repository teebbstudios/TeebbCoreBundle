<?php


namespace Teebb\CoreBundle\Services\Fields;

use Teebb\CoreBundle\AbstractService\AbstractField;
use Teebb\CoreBundle\Annotation\FieldType;
use Teebb\CoreBundle\Annotation\Translation;

/**
 * Boolean类型字段
 *
 * @FieldType(
 *     id="link",
 *     label=@Translation(message="teebb.core.field.link.label"),
 *     description=@Translation(message="teebb.core.field.link.description"),
 *     type="simple",
 *     category=@Translation(message="teebb.core.field.category.simple"),
 *     entity="Teebb\CoreBundle\Entity\Fields\LinkItem",
 *     formConfigType="Teebb\CoreBundle\Form\Type\FieldConfiguration\LinkItemConfigurationType",
 *     formConfigEntity="Teebb\CoreBundle\Entity\Fields\Configuration\LinkItemConfiguration",
 *     formType="Teebb\CoreBundle\Form\Type\FieldType\LinkFieldType"
 * )
 */
class LinkField extends AbstractField
{
    
}