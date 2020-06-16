<?php
/**
 * This file is part of the TeebbCoreBundle package.
 *
 * Author: Quan Weiwei <qww.zone@gmail.com>
 * Date: 2020/5/27
 * (c) teebbstudios
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Teebb\CoreBundle\Route;

use Teebb\CoreBundle\Metadata\EntityTypeMetadataInterface;

/**
 * EntityTypePathBuilder class
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class EntityTypePathBuilder implements RouteBuilderInterface
{
    public function build(RouteCollectionInterface $routeCollection): void
    {
        $routeCollection->addRoute(EntityTypeActions::INDEX);
        $routeCollection->addRoute(EntityTypeActions::CREATE);
        $routeCollection->addRoute(EntityTypeActions::UPDATE, '{alias}/update');
        $routeCollection->addRoute(EntityTypeActions::DELETE, '{alias}/delete');

        $routeCollection->addRoute(EntityTypeActions::INDEX_FIELD, '{alias}/fields');
        $routeCollection->addRoute(EntityTypeActions::ADD_FIELD, '{alias}/fields/add');
        $routeCollection->addRoute(EntityTypeActions::UPDATE_FIELD, '{alias}/fields/{fieldAlias}/update');
        $routeCollection->addRoute(EntityTypeActions::DELETE_FIELD, '{alias}/fields/{fieldAlias}/delete');
        $routeCollection->addRoute(EntityTypeActions::DISPLAY_FIELD, '{alias}/fields/display');
    }
}