<?php


namespace Teebb\CoreBundle\Voter\Types;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Teebb\CoreBundle\Voter\BaseVoter;

/**
 * 内容类型Voter
 */
class ContentEntityTypeVoter extends BaseVoter
{
    public const CONTENT_ENTITY_TYPE_INDEX = 'content_entity_type_index';
    public const CONTENT_ENTITY_TYPE_CREATE = 'content_entity_type_create';
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
            'teebb.core.voter.content_entity_type_create' => self::CONTENT_ENTITY_TYPE_CREATE,
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
        return $this->entityTypeVoteSupports($attribute, $subject, $this->getVoteOptionArray());
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        return $this->checkVoteOnAttribute($attribute, $subject, $token);
    }

}