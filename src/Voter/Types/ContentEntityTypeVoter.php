<?php


namespace Teebb\CoreBundle\Voter\Types;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Voter\BaseVoter;

/**
 * 内容类型Voter
 */
class ContentEntityTypeVoter extends BaseVoter
{
    public const CONTENT_ENTITY_TYPE_INDEX = 'content_entity_type_index';
    public const CONTENT_ENTITY_TYPE_CREATE = 'content_entity_type_create';

    /**
     * 获取当前voter所有权限
     * @return array
     */
    public function getVoteOptionArray(): array
    {
        $voteOptionArray = [
            'teebb.core.voter.content_entity_type_index' => self::CONTENT_ENTITY_TYPE_INDEX,
            'teebb.core.voter.content_entity_type_create' => self::CONTENT_ENTITY_TYPE_CREATE,
        ];

        return $this->getAllEntityTypesAttribute('content', $voteOptionArray);
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