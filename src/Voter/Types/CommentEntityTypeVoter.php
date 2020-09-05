<?php


namespace Teebb\CoreBundle\Voter\Types;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Teebb\CoreBundle\Voter\BaseVoter;

/**
 * 评论类型Voter
 */
class CommentEntityTypeVoter extends BaseVoter
{
    public const COMMENT_ENTITY_TYPE_INDEX = 'comment_entity_type_index';
    public const COMMENT_ENTITY_TYPE_CREATE = 'comment_entity_type_create';
    public const COMMENT_ENTITY_TYPE_UPDATE = 'comment_entity_type_update';
    public const COMMENT_ENTITY_TYPE_DELETE = 'comment_entity_type_delete';
    public const COMMENT_ENTITY_TYPE_INDEX_FIELDS = 'comment_entity_type_index_fields';
    public const COMMENT_ENTITY_TYPE_ADD_FIELD = 'comment_entity_type_add_field';
    public const COMMENT_ENTITY_TYPE_UPDATE_FIELD = 'comment_entity_type_update_field';
    public const COMMENT_ENTITY_TYPE_DELETE_FIELD = 'comment_entity_type_delete_field';
    public const COMMENT_ENTITY_TYPE_DISPLAY_FIELDS = 'comment_entity_type_display_fields';

    /**
     * 获取当前voter所有权限
     * @return array
     */
    public function getVoteOptionArray(): array
    {
        return [
            'teebb.core.voter.comment_entity_type_index' => self::COMMENT_ENTITY_TYPE_INDEX,
            'teebb.core.voter.comment_entity_type_create' => self::COMMENT_ENTITY_TYPE_CREATE,
            'teebb.core.voter.comment_entity_type_update' => self::COMMENT_ENTITY_TYPE_UPDATE,
            'teebb.core.voter.comment_entity_type_delete' => self::COMMENT_ENTITY_TYPE_DELETE,
            'teebb.core.voter.comment_entity_type_index_fields' => self::COMMENT_ENTITY_TYPE_INDEX_FIELDS,
            'teebb.core.voter.comment_entity_type_add_field' => self::COMMENT_ENTITY_TYPE_ADD_FIELD,
            'teebb.core.voter.comment_entity_type_update_field' => self::COMMENT_ENTITY_TYPE_UPDATE_FIELD,
            'teebb.core.voter.comment_entity_type_delete_field' => self::COMMENT_ENTITY_TYPE_DELETE_FIELD,
            'teebb.core.voter.comment_entity_type_display_fields' => self::COMMENT_ENTITY_TYPE_DISPLAY_FIELDS,
        ];
    }

    protected function supports(string $attribute, $subject)
    {
        return $this->entityTypeVoteSupports($attribute, $subject, $this->getVoteOptionArray());
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        return $this->checkVoteOnAttribute($attribute, $subject, $token);
    }

}