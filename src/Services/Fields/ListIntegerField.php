<?php


namespace Teebb\CoreBundle\Services\Fields;

use Teebb\CoreBundle\AbstractService\AbstractField;
use Teebb\CoreBundle\Annotation\FieldType;
use Teebb\CoreBundle\Annotation\Translation;

/**
 * ListInteger类型字段
 *
 * @FieldType(
 *     id="listInteger",
 *     label=@Translation(message="teebb.core.field.listInteger.label"),
 *     description=@Translation(message="teebb.core.field.listInteger.description"),
 *     type="numeric",
 *     category=@Translation(message="teebb.core.field.category.numeric"),
 *     entity="Teebb\CoreBundle\Entity\Fields\ListIntegerItem",
 *     formConfigType="Teebb\CoreBundle\Form\Type\FieldConfiguration\ListIntegerItemConfigurationType",
 *     formConfigEntity="Teebb\CoreBundle\Entity\Fields\Configuration\ListIntegerItemConfiguration",
 *     formType="Teebb\CoreBundle\Form\Type\FieldType\ListIntegerFieldType"
 * )
 */
class ListIntegerField extends AbstractField
{
    
}