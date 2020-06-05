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
use Teebb\CoreBundle\Annotation\Translation;

/**
 * Class CommentEntityType 评论类型
 *
 * @EntityType(
 *     label=@Translation(message="teebb.core.entity_type.comment.label"),
 *     type="comment",
 *     description=@Translation(message="teebb.core.entity_type.comment.description"),
 *     repository="repository",
 *     controller="Teebb\CoreBundle\Controller\Types\AbstractEntityTypeController",
 *     entity="Teebb\CoreBundle\Entity\Types\CommentType"
 * )
 */
class CommentEntityType extends AbstractEntityType
{

}