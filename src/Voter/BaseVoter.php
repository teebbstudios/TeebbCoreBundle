<?php


namespace Teebb\CoreBundle\Voter;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;
use Teebb\CoreBundle\Entity\Content;
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

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function __construct(Security $security, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    /**
     * 获取当前bundle所有内容类型,然后创建对应的voter attribute
     *
     * @param string $bundle
     * @param array $voteOptionArray
     * @return array
     */
    public function getAllEntityTypesAttribute(string $bundle, array $voteOptionArray)
    {
        $typesRepo = $this->entityManager->getRepository(Types::class);
        $types = $typesRepo->findBy(['bundle' => $bundle]);

        /**@var Types $type * */
        foreach ($types as $type) {
            $typeAlias = $type->getTypeAlias();
            $typeOptionArray = [
                $this->translator->trans('teebb.core.voter.' . $bundle . '_entity_type_update', ['%type%' => $type->getLabel()]) => $bundle . '_entity_type_' . $typeAlias . '_update',
                $this->translator->trans('teebb.core.voter.' . $bundle . '_entity_type_delete', ['%type%' => $type->getLabel()]) => $bundle . '_entity_type_' . $typeAlias . '_delete',
                $this->translator->trans('teebb.core.voter.' . $bundle . '_entity_type_index_field', ['%type%' => $type->getLabel()]) => $bundle . '_entity_type_' . $typeAlias . '_index_field',
                $this->translator->trans('teebb.core.voter.' . $bundle . '_entity_type_add_field', ['%type%' => $type->getLabel()]) => $bundle . '_entity_type_' . $typeAlias . '_add_field',
                $this->translator->trans('teebb.core.voter.' . $bundle . '_entity_type_update_field', ['%type%' => $type->getLabel()]) => $bundle . '_entity_type_' . $typeAlias . '_update_field',
                $this->translator->trans('teebb.core.voter.' . $bundle . '_entity_type_delete_field', ['%type%' => $type->getLabel()]) => $bundle . '_entity_type_' . $typeAlias . '_delete_field',
                $this->translator->trans('teebb.core.voter.' . $bundle . '_entity_type_display_field', ['%type%' => $type->getLabel()]) => $bundle . '_entity_type_' . $typeAlias . '_display_field',
            ];


            switch ($bundle) {
                case 'comment':
                    //如果bundle 为 comment 则需要添加 管理评论权限
                    $commentExtraPermissionArray = [
                        $this->translator->trans('teebb.core.voter.' . $bundle . '_entity_type_index_comments', ['%type%' => $type->getLabel()]) => $bundle . '_entity_type_' . $typeAlias . '_index_comments',
                    ];
                    $typeOptionArray = array_merge($commentExtraPermissionArray, $typeOptionArray);
                    break;
                case 'taxonomy':
                    //如果bundle 为 taxonomy 则需要添加 CRUD词汇
                    $taxonomyExtraPermissionArray = [
                        $this->translator->trans('teebb.core.voter.' . $bundle . '_entity_type_index_term', ['%type%' => $type->getLabel()]) => $bundle . '_entity_type_' . $typeAlias . '_index_term',
                        $this->translator->trans('teebb.core.voter.' . $bundle . '_entity_type_create_term', ['%type%' => $type->getLabel()]) => $bundle . '_entity_type_' . $typeAlias . '_create_term',
                        $this->translator->trans('teebb.core.voter.' . $bundle . '_entity_type_update_term', ['%type%' => $type->getLabel()]) => $bundle . '_entity_type_' . $typeAlias . '_update_term',
                        $this->translator->trans('teebb.core.voter.' . $bundle . '_entity_type_delete_term', ['%type%' => $type->getLabel()]) => $bundle . '_entity_type_' . $typeAlias . '_delete_term',
                    ];
                    $typeOptionArray = array_merge($taxonomyExtraPermissionArray, $typeOptionArray);
                    break;
            }

            $voteOptionArray = array_merge($voteOptionArray, $typeOptionArray);
        }

        return $voteOptionArray;
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
     * @param array $voterOptionArray
     * @return bool
     */
    public function baseVoteSupports(string $attribute, array $voterOptionArray)
    {
        $voterValueArray = [];
        foreach ($voterOptionArray as $label => $value) {
            $voterValueArray[] = $value;
        }

        if (!in_array($attribute, $voterValueArray)) {
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