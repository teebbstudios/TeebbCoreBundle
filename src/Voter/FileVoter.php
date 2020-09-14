<?php


namespace Teebb\CoreBundle\Voter;


use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Teebb\CoreBundle\Entity\FileManaged;

class FileVoter extends BaseVoter
{
    public const FILE_INDEX = 'file_index';
    public const FILE_UPLOAD = 'file_upload';
    public const FILE_DELETE = 'file_delete';

    public function getVoteOptionArray(): array
    {
        return [
//            'teebb.core.voter.file_index' => self::FILE_INDEX,
            'teebb.core.voter.file_upload' => self::FILE_UPLOAD,
            'teebb.core.voter.file_delete' => self::FILE_DELETE,
        ];
    }

    protected function supports(string $attribute, $subject)
    {
        if ($subject && !$subject instanceof FileManaged) {
            return false;
        }

        return $this->baseVoteSupports($attribute, $this->getVoteOptionArray());
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        return $this->checkVoteOnAttribute($attribute, $subject, $token);
    }

}