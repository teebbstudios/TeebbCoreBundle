<?php


namespace Teebb\CoreBundle\Services\Fields;

use Teebb\CoreBundle\AbstractService\AbstractField;
use Teebb\CoreBundle\Annotation\FieldType;
use Teebb\CoreBundle\Annotation\Translation;

/**
 * Comment类型字段
 *
 * @FieldType(
 *     id="comment",
 *     label=@Translation(message="teebb.core.field.comment.label"),
 *     description=@Translation(message="teebb.core.field.comment.description"),
 *     type="simple",
 *     category=@Translation(message="teebb.core.field.category.simple"),
 *     entity="Teebb\CoreBundle\Entity\Fields\CommentItem",
 *     formConfigType="Teebb\CoreBundle\Form\Type\FieldConfiguration\CommentItemConfigurationType",
 *     formConfigEntity="Teebb\CoreBundle\Entity\Fields\Configuration\CommentItemConfiguration",
 *     formType="Teebb\CoreBundle\Form\Type\FieldType\CommentFieldType"
 * )
 */
class CommentField extends AbstractField
{
    
}