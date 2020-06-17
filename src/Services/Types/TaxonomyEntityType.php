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
 * Class TaxonomyEntityType 分类类型
 *
 * @EntityType(
 *     label=@Translation(message="teebb.core.entity_type.taxonomy.label"),
 *     bundle="taxonomy",
 *     description=@Translation(message="teebb.core.entity_type.taxonomy.description"),
 *     repository="Teebb\CoreBundle\Repository\Types\EntityTypeRepository",
 *     controller="Teebb\CoreBundle\Controller\Types\AbstractEntityTypeController",
 *     entity="Teebb\CoreBundle\Entity\Types\Types",
 *     form=@TypesForm(formRows={
 *          @FormRow(
 *              property="label",formType="Symfony\Component\Form\Extension\Core\Type\TextType",
 *              options={
 *                  "label"="teebb.core.form.label",
 *                  "attr"={"class"="col-12 col-md-6 transliterate form-control-sm"},
 *                  "help"="teebb.core.form.types.label.help"
 *              }
 *          ),
 *          @FormRow(
 *              property="bundle",formType="Symfony\Component\Form\Extension\Core\Type\HiddenType"
 *          ),
 *          @FormRow(
 *              property="alias",formType="Symfony\Component\Form\Extension\Core\Type\TextType",
 *              isAlias=true,
 *              options={
 *                  "label"="teebb.core.form.alias",
 *                  "attr"={"class"="input-alias form-control form-control-sm col-12 col-md-6 form-control-sm"},
 *                  "help"="teebb.core.form.alias_help"
 *              }
 *          ),
 *          @FormRow(
 *              property="description",
 *              formType="Symfony\Component\Form\Extension\Core\Type\TextareaType",
 *              options={
 *                  "label"="teebb.core.form.description",
 *                  "attr"={"class"="form-control-sm w-100"},
 *                  "help"="teebb.core.form.types.description.help",
 *                  "required"=false
 *              }
 *          ),
 *     })
 * )
 */
class TaxonomyEntityType extends AbstractEntityType
{

}