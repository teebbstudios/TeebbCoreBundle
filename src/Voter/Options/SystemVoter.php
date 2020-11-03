<?php


namespace Teebb\CoreBundle\Voter\Options;


use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Teebb\CoreBundle\Voter\BaseVoter;

class SystemVoter extends BaseVoter
{
    public const SYSTEM_UPDATE = 'system_update';

    public function getVoteOptionArray(): array
    {
        return [
            'teebb.core.voter.system_update' => self::SYSTEM_UPDATE
        ];
    }

    protected function supports(string $attribute, $subject)
    {
        return $this->baseVoteSupports($attribute, $this->getVoteOptionArray());
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        return $this->checkVoteOnAttribute($attribute, $subject, $token);
    }

}