<?php


namespace Teebb\CoreBundle\Voter\Menu;


use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Teebb\CoreBundle\Entity\Menu;
use Teebb\CoreBundle\Voter\BaseVoter;

class MenuVoter extends BaseVoter
{
    public const MENU_INDEX = 'menu_index';
    public const MENU_CREATE = 'menu_create';

    public function getVoteOptionArray(): array
    {
        $voteOptionArray = [
            'teebb.core.voter.menu_index' => self::MENU_INDEX,
            'teebb.core.voter.menu_create' => self::MENU_CREATE
        ];

        $menuRepo = $this->entityManager->getRepository(Menu::class);
        $menus = $menuRepo->findAll();

        $menuPermissions = [];
        foreach ($menus as $menu) {
            $menuName = $menu->getName();
            $menuAlias = $menu->getMenuAlias();
            $menuPermission = [
                $this->translator->trans('teebb.core.voter.menu_update', ['%menu%' => $menuName]) => 'menu_' . $menuAlias . '_update',
                $this->translator->trans('teebb.core.voter.menu_delete', ['%menu%' => $menuName]) => 'menu_' . $menuAlias . '_delete',
                $this->translator->trans('teebb.core.voter.menu_item_manage', ['%menu%' => $menuName]) => 'menu_' . $menuAlias . '_item_manage',
                $this->translator->trans('teebb.core.voter.menu_item_save', ['%menu%' => $menuName]) => 'menu_' . $menuAlias . '_item_save',
                $this->translator->trans('teebb.core.voter.menu_item_add', ['%menu%' => $menuName]) => 'menu_' . $menuAlias . '_item_add',
                $this->translator->trans('teebb.core.voter.menu_item_remove', ['%menu%' => $menuName]) => 'menu_' . $menuAlias . '_item_remove',
            ];

            $menuPermissions = array_merge($menuPermissions, $menuPermission);
        }

        return array_merge($voteOptionArray, $menuPermissions);
    }

    protected function supports(string $attribute, $subject)
    {
        if ($subject && !$subject instanceof Menu) {
            return false;
        }

        return $this->baseVoteSupports($attribute, $this->getVoteOptionArray());
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        return $this->checkVoteOnAttribute($attribute, $subject, $token);
    }

}