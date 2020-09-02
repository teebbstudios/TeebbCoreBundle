<?php


namespace Teebb\CoreBundle\Services\Types;


use Teebb\CoreBundle\AbstractService\AbstractEntityType;
use Teebb\CoreBundle\Annotation\EntityType;
use Teebb\CoreBundle\Annotation\Translation;
use Teebb\CoreBundle\Annotation\TypesForm;
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
    public const PEOPLE_INDEX = 'people_index';
    public const PEOPLE_UPDATE = 'people_update';

    public const GROUP_INDEX = 'group_index';
    public const GROUP_CREATE = 'group_create';
    public const GROUP_UPDATE = 'group_update';
    public const GROUP_DELETE = 'group_delete';

    //User类型删除不需要的route
    protected function configureRoutes(EntityTypeRouteCollection $routeCollection): void
    {
        $routeCollection->remove('user_index');
        $routeCollection->remove('user_create');
        $routeCollection->remove('user_update');
        $routeCollection->remove('user_delete');

        //列表管理所有用户
        $routeCollection->addRoute(self::PEOPLE_INDEX, 'peoples');
        //编辑用户
        $routeCollection->addRoute(self::PEOPLE_UPDATE, 'people/{username}/update');

        //组
        $routeCollection->addRoute(self::GROUP_INDEX, 'groups');
        $routeCollection->addRoute(self::GROUP_CREATE, 'group/create');
        $routeCollection->addRoute(self::GROUP_UPDATE, 'group/{id}/update');
        $routeCollection->addRoute(self::GROUP_DELETE, 'group/{id}/delete');

    }
}