<?php


namespace Teebb\CoreBundle\Voter;


use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Teebb\CoreBundle\Entity\Formatter;

class FormatterVoter extends BaseVoter
{
    public const FORMATTER_INDEX = 'formatter_index';
    public const FORMATTER_CREATE = 'formatter_create';
    public const FORMATTER_UPDATE = 'formatter_update';
    public const FORMATTER_DELETE = 'formatter_delete';

    public function getVoteOptionArray(): array
    {
        return [
            'teebb.core.voter.formatter_index' => self::FORMATTER_INDEX,
            'teebb.core.voter.formatter_create' => self::FORMATTER_CREATE,
            'teebb.core.voter.formatter_update' => self::FORMATTER_UPDATE,
            'teebb.core.voter.formatter_delete' => self::FORMATTER_DELETE,
        ];
    }

    protected function supports(string $attribute, $subject)
    {
        if ($subject && !$subject instanceof Formatter) {
            return false;
        }

        return $this->baseVoteSupports($attribute, $this->getVoteOptionArray());
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        return $this->checkVoteOnAttribute($attribute, $subject, $token);
    }

}