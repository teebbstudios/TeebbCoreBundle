<?php


namespace Teebb\CoreBundle\Services\Fields;

use Teebb\CoreBundle\AbstractService\AbstractField;
use Teebb\CoreBundle\Annotation\FieldType;
use Teebb\CoreBundle\Annotation\Translation;

/**
 * @FieldType(
 *     id="text",
 *     label=@Translation(message="teebb.core.field.text.label"),
 *     description=@Translation(message="teebb.core.field.text.description"),
 *     type="writing",
 *     category=@Translation(message="teebb.core.field.category.text"),
 *     entity="Teebb\CoreBundle\Entity\Fields\TextItem",
 *     formConfigType="Teebb\CoreBundle\Form\Type\FieldConfiguration\TextItemConfigurationType",
 *     formConfigEntity="Teebb\CoreBundle\Entity\Fields\Configuration\TextItemConfiguration",
 *     formType="Teebb\CoreBundle\Form\Type\FieldType\TextFieldType"
 * )
 */
class TextField extends AbstractField
{
    
}