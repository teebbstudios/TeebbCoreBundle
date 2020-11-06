<?php


namespace Teebb\CoreBundle\Twig\Extensions;


use Symfony\Component\Security\Core\Security;
use Teebb\CoreBundle\Entity\User;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('is_granted_affirmative', [$this, 'isGrantedAffirmative'])
        ];
    }

    /**
     * 判断当前用户是否在允许的组内
     * @param array $groups
     * @return bool
     */
    public function isGrantedAffirmative(array $groups): bool
    {
        /**@var User $user * */
        $user = $this->security->getUser();

        $groups = $user->getGroups();
        foreach ($groups as $group) {
            if (in_array($group->getGroupAlias(), $groups) || $group->getGroupAlias() == 'super_admin') {
                return true;
            }
        }
        return false;
    }
}