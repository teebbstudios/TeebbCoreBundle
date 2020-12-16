<?php


namespace Teebb\CoreBundle\Voter\Content;


use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Teebb\CoreBundle\Entity\Content;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Voter\BaseVoter;

class ContentVoter extends BaseVoter
{
    public const CONTENT_INDEX = 'content_index';
    public const CONTENT_OWNER_UPDATE = 'content_owner_update';
    public const CONTENT_OWNER_DELETE = 'content_owner_delete';

    public function getVoteOptionArray(): array
    {
        $voteOptionArray = [
            'teebb.core.voter.content_index' => self::CONTENT_INDEX,
            'teebb.core.voter.content_owner_update' => self::CONTENT_OWNER_UPDATE,
            'teebb.core.voter.content_owner_delete' => self::CONTENT_OWNER_DELETE,
        ];

        $typesRepo = $this->entityManager->getRepository(Types::class);
        $types = $typesRepo->findBy(['bundle' => 'content']);

        $contentPermissions = [];
        /**@var Types $type * */
        foreach ($types as $type) {
            $typeAlias = $type->getTypeAlias();
            $contentPermission = [
                $this->translator->trans('teebb.core.voter.content_create', ['%type%' => $type->getLabel()]) => 'content_' . $typeAlias . '_create',
                $this->translator->trans('teebb.core.voter.content_update', ['%type%' => $type->getLabel()]) => 'content_' . $typeAlias . '_update',
                $this->translator->trans('teebb.core.voter.content_delete', ['%type%' => $type->getLabel()]) => 'content_' . $typeAlias . '_delete',
            ];

            $contentPermissions = array_merge($contentPermissions, $contentPermission);
        }

        return array_merge($voteOptionArray, $contentPermissions);
    }

    protected function supports(string $attribute, $subject): bool
    {
        if ($subject && !$subject instanceof Content) {
            return false;
        }

        return $this->baseVoteSupports($attribute, $this->getVoteOptionArray());
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        //用户组有此权限
        if ($this->checkVoteOnAttribute($attribute, $subject, $token)) {
            //用户有编辑自己的内容 删除自己的内容权限
            if (in_array($attribute, ['content_owner_update', 'content_owner_delete'])) {
                /**@var Content $subject * */
                if ($subject->getAuthor() === $this->security->getUser()) {
                    return true;
                }
                return false;
            }
            return true;
        }
        return false;

    }

}