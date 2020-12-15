<?php


namespace Teebb\CoreBundle\Voter;


use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Teebb\CoreBundle\Entity\FileManaged;

class FileVoter extends BaseVoter
{
    public const FILE_INDEX = 'file_index';
    public const FILE_UPLOAD = 'file_upload';
    public const FILE_DELETE = 'file_delete';
    public const FILE_OWNER_DELETE = 'file_owner_delete';

    public function getVoteOptionArray(): array
    {
        return [
//            'teebb.core.voter.file_index' => self::FILE_INDEX,
            'teebb.core.voter.file_upload' => self::FILE_UPLOAD,
            'teebb.core.voter.file_delete' => self::FILE_DELETE,
            'teebb.core.voter.file_owner_delete' => self::FILE_OWNER_DELETE,
        ];
    }

    protected function supports(string $attribute, $subject): bool
    {
        if ($subject && !$subject instanceof FileManaged) {
            return false;
        }

        return $this->baseVoteSupports($attribute, $this->getVoteOptionArray());
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        //如果是删除自己上传的文件
        if ($attribute === self::FILE_OWNER_DELETE){
           if ($subject->getAuthor() === $this->security->getUser())
           {
               return true;
           }
           return false;
        }

        return $this->checkVoteOnAttribute($attribute, $subject, $token);
    }

}