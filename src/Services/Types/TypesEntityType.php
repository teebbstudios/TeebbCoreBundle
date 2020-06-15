<?php
/**
 * This file is part of the TeebbCoreBundle package.
 *
 * Author: Quan Weiwei <qww.zone@gmail.com>
 * Date: 2020/5/29
 * (c) teebbstudios
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Teebb\CoreBundle\Services\Types;


use Teebb\CoreBundle\AbstractService\AbstractEntityType;
use Teebb\CoreBundle\Annotation\EntityType;
use Teebb\CoreBundle\Annotation\FormRow;
use Teebb\CoreBundle\Annotation\Translation;
use Teebb\CoreBundle\Annotation\TypesForm;

/**
 * Class TypesEntityType 内容类型
 *
 * @EntityType(
 *     label=@Translation(message="teebb.core.entity_type.content.label"),
 *     type="types",
 *     description=@Translation(message="teebb.core.entity_type.content.description"),
 *     repository="Teebb\CoreBundle\Repository\Types\TypesEntityTypeRepository",
 *     controller="Teebb\CoreBundle\Controller\Types\AbstractEntityTypeController",
 *     entity="Teebb\CoreBundle\Entity\Types\ContentType",
 *     form=@TypesForm(formRows={
 *          @FormRow(
 *              property="label",formType="Symfony\Component\Form\Extension\Core\Type\TextType",
 *              options={"label"="abc", "required"=true}
 *          ),
 *          @FormRow(
 *              property="type",formType="Symfony\Component\Form\Extension\Core\Type\HiddenType",
 *              options={"label"="alias"}
 *          ),
 *          @FormRow(
 *              property="description",
 *              formType="Symfony\Component\Form\Extension\Core\Type\TextareaType"
 *          ),
 *     })
 *
 * )
 */
class TypesEntityType extends AbstractEntityType
{

}