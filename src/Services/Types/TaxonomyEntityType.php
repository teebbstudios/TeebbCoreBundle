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
 * Class TaxonomyEntityType
 *
 * @EntityType(
 *     label=@Translation(message="teebb.core.entity_type.taxonomy.label"),
 *     alias="taxonomy",
 *     description=@Translation(message="teebb.core.entity_type.taxonomy.description"),
 *     repository="repository",
 *     controller="Teebb\CoreBundle\Controller\Types\AbstractEntityTypeController",
 *     entity="Teebb\CoreBundle\Entity\Types\TaxonomyType"
 * )
 */
class TaxonomyEntityType extends AbstractEntityType
{

}