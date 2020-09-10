<?php


namespace Teebb\CoreBundle\Voter\Types;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Voter\BaseVoter;

/**
 * 评论类型Voter
 */
class CommentEntityTypeVoter extends BaseVoter
{
    public const COMMENT_ENTITY_TYPE_INDEX = 'comment_entity_type_index';
    public const COMMENT_ENTITY_TYPE_CREATE = 'comment_entity_type_create';

    /**
     * 获取当前voter所有权限
     * @return array
     */
    public function getVoteOptionArray(): array
    {
        $voteOptionArray = [
            'teebb.core.voter.comment_entity_type_index' => self::COMMENT_ENTITY_TYPE_INDEX,
            'teebb.core.voter.comment_entity_type_create' => self::COMMENT_ENTITY_TYPE_CREATE,
        ];

        return $this->getAllEntityTypesAttribute('comment', $voteOptionArray);
    }

    protected function supports(string $attribute, $subject)
    {
        if ($subject && !$subject instanceof Types) {
            return false;
        }

        return $this->baseVoteSupports($attribute, $this->getVoteOptionArray());
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        return $this->checkVoteOnAttribute($attribute, $subject, $token);
    }

}