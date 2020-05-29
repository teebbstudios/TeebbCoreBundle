<?php
/**
 * This file is part of the TeebbCoreBundle package.
 *
 * Author: Quan Weiwei <qww.zone@gmail.com>
 * Date: 2020/5/21
 * (c) teebbstudios
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Teebb\CoreBundle\Entity\Types;

use Teebb\CoreBundle\Annotation\EntityType;
use Teebb\CoreBundle\Annotation\Translation;

/**
 * 评论类型
 *
 * @EntityType(
 *     name=@Translation(message="teebb.core.entity_type.comment.name", domain="TeebbCoreBundle"),
 *     alias="comment",
 *     description=@Translation(message="teebb.core.entity_type.comment.description", domain="TeebbCoreBundle"),
 *     repository="repository",
 *     controller="Teebb\CoreBundle\Controller\Types\AbstractEntityTypeController",
 *     service="Teebb\CoreBundle\Services\Types\CommentEntityType"
 * )
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class CommentType
{

}