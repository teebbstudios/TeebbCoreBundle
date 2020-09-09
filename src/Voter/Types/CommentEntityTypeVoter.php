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