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
 * Class ContentEntityType 内容类型
 *
 * @EntityType(
 *     label=@Translation(message="teebb.core.entity_type.content.label"),
 *     bundle="content",
 *     description=@Translation(message="teebb.core.entity_type.content.description"),
 *     repository="Teebb\CoreBundle\Repository\Types\EntityTypeRepository",
 *     controller="Teebb\CoreBundle\Controller\Types\ContentTypeController",
 *     typeEntity="Teebb\CoreBundle\Entity\Types\Types",
 *     entity="Teebb\CoreBundle\Entity\Content",
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
 *              property="typeAlias",formType="Symfony\Component\Form\Extension\Core\Type\TextType",
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
 *          @FormRow(
 *              property="saveAndAddFields",
 *              formType="Symfony\Component\Form\Extension\Core\Type\SubmitType",
 *              options={
 *                  "label"="teebb.core.form.types_save_and_add_fields",
 *                  "label_html"=true,
 *                  "attr"={"class"="btn btn-primary btn-sm btn-icon-split"},
 *                  "row_attr"={"class"="pr-2"},
 *              }
 *          ),
 *          @FormRow(
 *              property="save",
 *              formType="Symfony\Component\Form\Extension\Core\Type\SubmitType",
 *              options={
 *                  "label"="teebb.core.form.types_save",
 *                  "label_html"=true,
 *                  "attr"={"class"="btn btn-success btn-sm btn-icon-split"},
 *                  "row_attr"={"class"="pr-2"},
 *              }
 *          ),
 *     }),
 *     entityFormType="Teebb\CoreBundle\Form\Type\Content\ContentType"
 *
 * )
 */
class ContentEntityType extends AbstractEntityType
{

}