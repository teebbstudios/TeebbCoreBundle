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
 * Class ContentEntityType
 *
 * @EntityType(
 *     label=@Translation(message="teebb.core.entity_type.content.label"),
 *     alias="types",
 *     description=@Translation(message="teebb.core.entity_type.content.description"),
 *     repository="repository",
 *     controller="Teebb\CoreBundle\Controller\Types\AbstractEntityTypeController",
 *     entity="Teebb\CoreBundle\Entity\Types\ContentType"
 * )
 */
class ContentEntityType extends AbstractEntityType
{

}