<?php


namespace Teebb\CoreBundle\Voter\Types;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Voter\BaseVoter;

/**
 * 分类类型Voter
 */
class TaxonomyEntityTypeVoter extends BaseVoter
{
    public const TAXONOMY_ENTITY_TYPE_INDEX = 'taxonomy_entity_type_index';
    public const TAXONOMY_ENTITY_TYPE_CREATE= 'taxonomy_entity_type_create';
    public const TAXONOMY_ENTITY_TYPE_UPDATE = 'taxonomy_entity_type_update';
    public const TAXONOMY_ENTITY_TYPE_DELETE = 'taxonomy_entity_type_delete';

    /**
     * 获取当前voter所有权限
     * @return array
     */
    public function getVoteOptionArray(): array
    {
        $voteOptionArray = [
            'teebb.core.voter.taxonomy_entity_type_index' => self::TAXONOMY_ENTITY_TYPE_INDEX,
            'teebb.core.voter.taxonomy_entity_type_create' => self::TAXONOMY_ENTITY_TYPE_CREATE,
            'teebb.core.voter.taxonomy_entity_type_update' => self::TAXONOMY_ENTITY_TYPE_UPDATE,
            'teebb.core.voter.taxonomy_entity_type_delete' => self::TAXONOMY_ENTITY_TYPE_DELETE,
        ];

        return $this->getAllEntityTypesAttribute('taxonomy', $voteOptionArray);
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