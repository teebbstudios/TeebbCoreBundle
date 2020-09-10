<?php


namespace Teebb\CoreBundle\Voter\User;


use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Teebb\CoreBundle\Entity\User;
use Teebb\CoreBundle\Voter\BaseVoter;

class UserVoter extends BaseVoter
{
    public const USER_ENTITY_TYPE_INDEX_FIELD = 'user_entity_type_people_index_field';
    public const USER_ENTITY_TYPE_ADD_FIELD = 'user_entity_type_people_add_field';
    public const USER_ENTITY_TYPE_UPDATE_FIELD = 'user_entity_type_people_update_field';
    public const USER_ENTITY_TYPE_DELETE_FIELD = 'user_entity_type_people_delete_field';
    public const USER_ENTITY_TYPE_DISPLAY_FIELD = 'user_entity_type_people_display_field';
    public const USER_ENTITY_TYPE_PEOPLE_INDEX = 'user_entity_type_people_index';
    public const USER_ENTITY_TYPE_PEOPLE_UPDATE = 'user_entity_type_people_update';
    public const USER_ENTITY_TYPE_GROUP_INDEX = 'user_entity_type_group_index';
    public const USER_ENTITY_TYPE_GROUP_CREATE = 'user_entity_type_group_create';
    public const USER_ENTITY_TYPE_GROUP_UPDATE = 'user_entity_type_group_update';
    public const USER_ENTITY_TYPE_GROUP_DELETE = 'user_entity_type_group_delete';

    public function getVoteOptionArray(): array
    {
        return [
            'teebb.core.voter.user_entity_type_index_field' => self::USER_ENTITY_TYPE_INDEX_FIELD,
            'teebb.core.voter.user_entity_type_add_field' => self::USER_ENTITY_TYPE_ADD_FIELD,
            'teebb.core.voter.user_entity_type_update_field' => self::USER_ENTITY_TYPE_UPDATE_FIELD,
            'teebb.core.voter.user_entity_type_delete_field' => self::USER_ENTITY_TYPE_DELETE_FIELD,
            'teebb.core.voter.user_entity_type_display_field' => self::USER_ENTITY_TYPE_DISPLAY_FIELD,
            'teebb.core.voter.user_entity_type_people_index' => self::USER_ENTITY_TYPE_PEOPLE_INDEX,
            'teebb.core.voter.user_entity_type_people_update' => self::USER_ENTITY_TYPE_PEOPLE_UPDATE,
            'teebb.core.voter.user_entity_type_group_index' => self::USER_ENTITY_TYPE_GROUP_INDEX,
            'teebb.core.voter.user_entity_type_group_create' => self::USER_ENTITY_TYPE_GROUP_CREATE,
            'teebb.core.voter.user_entity_type_group_update' => self::USER_ENTITY_TYPE_GROUP_UPDATE,
            'teebb.core.voter.user_entity_type_group_delete' => self::USER_ENTITY_TYPE_GROUP_DELETE,
        ];
    }

    protected function supports(string $attribute, $subject)
    {
        if (!in_array($attribute, $this->getVoteOptionArray())) {
            return false;
        }

        if ($subject && !$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        return $this->checkVoteOnAttribute($attribute, $subject, $token);
    }

}