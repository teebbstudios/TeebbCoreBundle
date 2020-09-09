<?php


namespace Teebb\CoreBundle\Voter;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Teebb\CoreBundle\Entity\Group;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Entity\User;

/**
 * 所有voter继承此类
 */
abstract class BaseVoter extends Voter implements TeebbVoterInterface
{
    /**
     * @var Security
     */
    protected $security;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
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
     * @return bool
     */
    public function checkUserGroupsHasAttribute(Group $group, string $attribute)
    {
        $groupPermissions = $group->getPermissions();

        if (!empty($groupPermissions) && in_array($attribute, $groupPermissions['permission'])) {
            return true;
        }

        return false;
    }

    /**
     * 内容类型vote Supports检查
     * @param string $attribute
     * @param $subject
     * @param array $voterOptionArray
     * @return bool
     */
    public function entityTypeVoteSupports(string $attribute, $subject, array $voterOptionArray)
    {
        if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
            return true;
        }

        $voterValueArray = [];
        foreach ($voterOptionArray as $label => $value) {
            $voterValueArray[] = $value;
        }

        if (!in_array($attribute, $voterValueArray)) {
            return false;
        }

        if ($subject && !$subject instanceof Types) {
            return false;
        }

        return true;
    }

    /**
     * 内容类型vote VoteOnAttribute检查
     * @param string $attribute
     * @param $subject
     * @param TokenInterface $token
     * @return bool
     */
    public function checkVoteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        /**@var User $user * */
        $user = $this->security->getUser();

        $groups = $user->getGroups();

        foreach ($groups as $group) {
            //如果当前用户所在组包含超级管理员角色
            if ($this->checkUserInSuperAdminGroup($group)) {
                return true;
            }

            //如果当前用户所在组权限允许当前$attribute
            if ($this->checkUserGroupsHasAttribute($group, $attribute)) {
                return true;
            }
        }

        return false;
    }
}