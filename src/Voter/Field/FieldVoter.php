<?php


namespace Teebb\CoreBundle\Voter\Field;


use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Teebb\CoreBundle\Voter\BaseVoter;

class FieldVoter extends BaseVoter
{
    public const FIELD_ITEM_DELETE = 'field_item_delete';
    public const FIELD_ITEM_OWNER_DELETE = 'field_item_owner_delete';

    public function getVoteOptionArray(): array
    {
        return [
            'teebb.core.voter.field_item_delete' => self::FIELD_ITEM_DELETE, //删除字段权限
        ];
    }

    protected function supports(string $attribute, $subject): bool
    {
        return $this->baseVoteSupports($attribute, $this->getVoteOptionArray());
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        return $this->checkVoteOnAttribute($attribute, $subject, $token);
    }

}