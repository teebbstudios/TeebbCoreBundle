<?php


namespace Teebb\CoreBundle\Services\Types;


use Teebb\CoreBundle\AbstractService\AbstractEntityType;
use Teebb\CoreBundle\Annotation\EntityType;
use Teebb\CoreBundle\Annotation\Translation;
use Teebb\CoreBundle\Annotation\TypesForm;
use Teebb\CoreBundle\Route\EntityTypeActions;
use Teebb\CoreBundle\Route\EntityTypeRouteCollection;

/**
 * Class User 类型
 *
 * @EntityType(
 *     label=@Translation(message="teebb.core.entity_type.user.label"),
 *     bundle="user",
 *     description=@Translation(message="teebb.core.entity_type.user.description"),
 *     repository="Teebb\CoreBundle\Repository\Types\EntityTypeRepository",
 *     controller="Teebb\CoreBundle\Controller\Types\UserTypeController",
 *     typeEntity="Teebb\CoreBundle\Entity\Types\Types",
 *     entity="\Teebb\CoreBundle\Entity\User",
 *     form=@TypesForm(formRows={}),
 *     entityFormType="Teebb\CoreBundle\Form\Type\Content\UserType"
 * )
 */
class UserEntityType extends AbstractEntityType
{
    //User类型删除不需要的route
    protected function configureRoutes(EntityTypeRouteCollection $routeCollection): void
    {
        $routeCollection->remove('user_index');
        $routeCollection->remove('user_create');
        $routeCollection->remove('user_update');
        $routeCollection->remove('user_delete');
    }
}