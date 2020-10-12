<?php


namespace Teebb\CoreBundle\Menu;


use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Security;
use Teebb\CoreBundle\Entity\User;

class MenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;
    /**
     * @var Security
     */
    private $security;

    public function __construct(FactoryInterface $factory, ParameterBagInterface $parameterBag, Security $security)
    {
        $this->factory = $factory;
        $this->parameterBag = $parameterBag;
        $this->security = $security;
    }

    public function createSidebarMenu(array $options): ItemInterface
    {
        /**@var User $user * */
        $user = $this->security->getUser();

        $sidebarMenuGroups = $this->parameterBag->get('teebb.core.menu.sidebar_menu');

        $menu = $this->factory->createItem('root');

        //$groups 用于存储菜单分组，比如：内容，其他，设置
        $groups = [];
        foreach ($sidebarMenuGroups as $groupName => $menuGroups) {
            $groups[] = $groupName;
            $categoryMenuWrapper = $this->factory->createItem($groupName); //添加分类菜单
            //$category 每个菜单折叠项的分类名称， $items 当前菜单折叠项下的菜单信息
            foreach ($menuGroups as $category => $item) {
                //添加折叠菜单项
                $categoryMenuItem = null;
                if ($this->canGenerateMenuItem($user, $item['groups'])) {
                    $categoryMenuItem = $categoryMenuWrapper->addChild($this->factory->createItem($item['label']));
                    $categoryMenuItem->setExtra('translation_domain', isset($item['label_catalogue']) ? $item['label_catalogue'] : 'messages');
                    $categoryMenuItem->setExtra('icon', $item['icon']);
                    $categoryMenuItem->setExtra('category', $category);
                }
                //添加折叠菜单子菜单
                foreach ($item['items'] as $menuItem)
                {
                    if ($this->canGenerateMenuItem($user, $menuItem['groups']))
                    {
                        $categoryMenuItem->addChild($this->factory->createItem($menuItem['label'],[
                            'route' => $menuItem['route'],
                            'routeParameters' => $menuItem['route_params'],
                            'routeAbsolute' => $menuItem['route_absolute'],
                            'extras' => [
                                'translation_domain' => isset($menuItem['label_catalogue']) ? $menuItem['label_catalogue'] : 'messages'
                            ],
                        ]));
                    }
                }
            }

            $menu->addChild($categoryMenuWrapper);
        }

        $menu->setExtra('groups', $groups);

        return $menu;
    }

    /**
     * 根据当前用户所在用户组判断是否可以生成此菜单项
     * @param User $user ，
     * @param array $itemGroups 当前菜单允许的所有用户组
     * @return bool
     */
    private function canGenerateMenuItem(User $user, array $itemGroups): bool
    {
        $userGroups = $user->getGroups();

        foreach ($userGroups as $userGroup) {
            // 当前用户如果是超级管理员组super_admin 则可生成所有菜单
            if ($userGroup->getGroupAlias() == 'super_admin') {
                return true;
            }
            // 判断当前用户组在item group数组中
            if (in_array($userGroup, $itemGroups)) {
                return true;
            }
        }

        return false;
    }
}