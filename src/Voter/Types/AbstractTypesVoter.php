<?php


namespace Teebb\CoreBundle\Voter\Types;

use Symfony\Component\Security\Core\Security;
use Teebb\CoreBundle\Entity\Group;
use Teebb\CoreBundle\Entity\User;
use Teebb\CoreBundle\Voter\BaseVoter;

/**
 * 内容类型Voter抽象类
 */
abstract class AbstractTypesVoter extends BaseVoter
{
    /**
     * @var Security
     */
    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * 检查当前用户是否有超级管理员角色
     * @param Group $group
     * @return bool
     */
    public function checkUserInSuperAdminGroup(Group $group)
    {
        if ($group->hasRole('ROLE_SUPER_ADMIN')) {
            return true;
        }

        return false;
    }

    /**
     * 检查当前用户组权限是否允许当前$attribute
     * @param Group $group
     * @param string $attribute
     */
    public function checkUserGroupsHasAttribute(Group $group, string $attribute)
    {
        $groupPermissions = $group->getPermissions();
        dd($groupPermissions);
    }
}