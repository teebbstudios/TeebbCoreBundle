<?php


namespace Teebb\CoreBundle\Voter\Types;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * 评论类型Voter
 */
class CommentEntityTypeVoter extends AbstractTypesVoter
{
    public const COMMENT_ENTITY_TYPE_INDEX = 'comment_entity_type_index';
    public const COMMENT_ENTITY_TYPE_NEW = 'comment_entity_type_new';
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
            'teebb.core.voter.comment_entity_type_new' => self::COMMENT_ENTITY_TYPE_NEW,
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
        // TODO: Implement supports() method.
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        // TODO: Implement voteOnAttribute() method.
    }

}