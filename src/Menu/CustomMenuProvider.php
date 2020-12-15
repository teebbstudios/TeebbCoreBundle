<?php


namespace Teebb\CoreBundle\Menu;


use Doctrine\ORM\EntityManagerInterface;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\Provider\MenuProviderInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Teebb\CoreBundle\Entity\Menu;
use Teebb\CoreBundle\Entity\MenuItem;
use Teebb\CoreBundle\Event\MenuCacheEvent;
use Teebb\CoreBundle\Exception\InvalidMenuAliasException;

class CustomMenuProvider implements MenuProviderInterface
{
    /**
     * @var FactoryInterface
     */
    private $factory;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    private $menuRepository;

    private $menuItemRepository;

    public function __construct(FactoryInterface $factory, EntityManagerInterface $entityManager)
    {
        $this->factory = $factory;
        $this->entityManager = $entityManager;
        $this->menuRepository = $this->entityManager->getRepository(Menu::class);
        $this->menuItemRepository = $this->entityManager->getRepository(MenuItem::class);
    }

    /**
     * @param string $name
     * @param array $options
     * @return ItemInterface
     * @throws InvalidMenuAliasException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function get(string $name, array $options = []): ItemInterface
    {
        $menuObject = $this->findMenuObject($name);

        if ($menuObject == null) {
            throw new InvalidMenuAliasException(sprintf('The menu "%s" is not defined.', $name));
        }

        $menu = $this->factory->createItem($name);

        $rootMenuItem = $this->menuItemRepository->findOneBy(['menu' => $menuObject]);

        return $this->addMenuItems($rootMenuItem, $menu);
    }

    public function has(string $name, array $options = []): bool
    {
        $menuObject = $this->findMenuObject($name);

        return $menuObject !== null;
    }

    /**
     * 查询当前菜单名称对应的Menu对象
     * @param string $menuAlias
     * @return Menu|null
     */
    public function findMenuObject(string $menuAlias)
    {
        return $this->menuRepository->findOneBy(['menuAlias' => $menuAlias]);
    }

    /**
     * 获取每层MenuItem的子菜单并添加到ItemInterface
     * @param MenuItem $menuItem
     * @param ItemInterface $menu
     * @return ItemInterface
     */
    public function addMenuItems(MenuItem $menuItem, ItemInterface $menu): ItemInterface
    {
        $childMenuItems = $menuItem->getChildren();

        foreach ($childMenuItems as $childMenuItem) {
            $menuItem = $menu->addChild($this->generateMenuItem($childMenuItem));
            $this->addMenuItems($childMenuItem, $menuItem);
        }

        return $menu;
    }

    /**
     * @param MenuItem $menuItem
     * @return ItemInterface
     */
    public function generateMenuItem(MenuItem $menuItem)
    {
        return $this->factory->createItem($menuItem->getMenuTitle(), [
            'uri' => $menuItem->getMenuLink(),
            'linkAttributes' => [
                'title' => $menuItem->getMenuTitleAttr() ?: $menuItem->getMenuTitle()
            ]
        ]);
    }
}