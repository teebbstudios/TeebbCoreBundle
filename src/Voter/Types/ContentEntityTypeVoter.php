<?php


namespace Teebb\CoreBundle\Voter\Types;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Entity\User;

/**
 * 内容类型Voter
 */
class ContentEntityTypeVoter extends AbstractTypesVoter
{
    public const CONTENT_ENTITY_TYPE_INDEX = 'content_entity_type_index';
    public const CONTENT_ENTITY_TYPE_NEW = 'content_entity_type_new';
    public const CONTENT_ENTITY_TYPE_UPDATE = 'content_entity_type_update';
    public const CONTENT_ENTITY_TYPE_DELETE = 'content_entity_type_delete';
    public const CONTENT_ENTITY_TYPE_INDEX_FIELDS = 'content_entity_type_index_fields';
    public const CONTENT_ENTITY_TYPE_ADD_FIELD = 'content_entity_type_add_field';
    public const CONTENT_ENTITY_TYPE_UPDATE_FIELD = 'content_entity_type_update_field';
    public const CONTENT_ENTITY_TYPE_DELETE_FIELD = 'content_entity_type_delete_field';
    public const CONTENT_ENTITY_TYPE_DISPLAY_FIELDS = 'content_entity_type_display_fields';

    /**
     * 获取当前voter所有权限
     * @return array
     */
    public function getVoteOptionArray(): array
    {
        return [
            'teebb.core.voter.content_entity_type_index' => self::CONTENT_ENTITY_TYPE_INDEX,
            'teebb.core.voter.content_entity_type_new' => self::CONTENT_ENTITY_TYPE_NEW,
            'teebb.core.voter.content_entity_type_update' => self::CONTENT_ENTITY_TYPE_UPDATE,
            'teebb.core.voter.content_entity_type_delete' => self::CONTENT_ENTITY_TYPE_DELETE,
            'teebb.core.voter.content_entity_type_index_fields' => self::CONTENT_ENTITY_TYPE_INDEX_FIELDS,
            'teebb.core.voter.content_entity_type_add_field' => self::CONTENT_ENTITY_TYPE_ADD_FIELD,
            'teebb.core.voter.content_entity_type_update_field' => self::CONTENT_ENTITY_TYPE_UPDATE_FIELD,
            'teebb.core.voter.content_entity_type_delete_field' => self::CONTENT_ENTITY_TYPE_DELETE_FIELD,
            'teebb.core.voter.content_entity_type_display_fields' => self::CONTENT_ENTITY_TYPE_DISPLAY_FIELDS,
        ];
    }

    protected function supports(string $attribute, $subject)
    {
        if (!in_array($attribute, $this->getVoteOptionArray())) {
            return false;
        }

        if ($subject && !$subject instanceof Types) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        /**@var User $user**/
        $user = $this->security->getUser();

        $groups = $user->getGroups();

        foreach ($groups as $group) {
            //如果当前用户所在组包含超级管理员角色
            if($this->checkUserInSuperAdminGroup($group)){
                return true;
            }

            //如果当前用户所在组权限允许当前$attribute
            if ($this->checkUserGroupsHasAttribute($group, $attribute))
            {
                return true;
            }
        }

        return false;
    }

}