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
 * 分类类型
 *
 * @EntityType(
 *     label=@Translation(message="teebb.core.entity_type.taxonomy.name", domain="TeebbCoreBundle"),
 *     alias="taxonomy",
 *     description=@Translation(message="teebb.core.entity_type.taxonomy.description", domain="TeebbCoreBundle"),
 *     repository="repository",
 *     controller="Teebb\CoreBundle\Controller\Types\AbstractEntityTypeController",
 *     service="Teebb\CoreBundle\Services\Types\TaxonomyEntityType"
 * )
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class TaxonomyType
{

}