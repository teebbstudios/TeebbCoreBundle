<?php


namespace Teebb\CoreBundle\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Teebb\CoreBundle\Entity\Content;
use Teebb\CoreBundle\Entity\Menu;
use Teebb\CoreBundle\Entity\MenuItem;
use Teebb\CoreBundle\Entity\Taxonomy;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Event\MenuCacheEvent;
use Teebb\CoreBundle\Form\FormContractorInterface;
use Teebb\CoreBundle\Form\Type\Menu\MenuType;
use Teebb\CoreBundle\Templating\TemplateRegistry;
use Symfony\Component\HttpFoundation\Response;

/**
 * 菜单管理Controller
 */
class MenuController extends AbstractController
{
    /**
     * @var TemplateRegistry
     */
    private $templateRegistry;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var FormContractorInterface
     */
    private $formContractor;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(EntityManagerInterface $entityManager, TemplateRegistry $templateRegistry,
                                FormContractorInterface $formContractor, EventDispatcherInterface $eventDispatcher)
    {
        $this->templateRegistry = $templateRegistry;
        $this->entityManager = $entityManager;
        $this->formContractor = $formContractor;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function indexAction(Request $request)
    {
        $this->denyAccessUnlessGranted('menu_index');

        $menuRepo = $this->entityManager->getRepository(Menu::class);

        $menus = $menuRepo->findAll();

        return $this->render($this->templateRegistry->getTemplate('index', 'menu'), [
            'menus' => $menus,
            'action' => 'index'
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $this->denyAccessUnlessGranted('menu_create');

        $form = $this->createForm(MenuType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**@var Menu $menu * */
            $menu = $form->getData();

            $menuItem = new MenuItem();
            $menuItem->setMenu($menu);

            $this->entityManager->persist($menu);
            $this->entityManager->persist($menuItem);

            $this->entityManager->flush();

            $this->addFlash('success', $this->container->get('translator')->trans(
                'teebb.core.menu.create_success', ['%value%' => $menu->getName()]
            ));

            return $this->redirectToRoute('teebb_menu_index');
        }
        return $this->render($this->templateRegistry->getTemplate('create', 'menu'), [
            'form' => $form->createView(),
            'extra_assets' => ['transliteration'], //当前页面需要额外添加的assets库
            'action' => 'create'
        ]);
    }

    /**
     * @param Request $request
     * @param Menu $menu
     * @return Response
     */
    public function updateAction(Request $request, Menu $menu)
    {
        $this->denyAccessUnlessGranted('menu_' . $menu->getMenuAlias() . '_update');

        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**@var Menu $menu * */
            $menu = $form->getData();
            $this->entityManager->persist($menu);
            $this->entityManager->flush();

            $this->addFlash('success', $this->container->get('translator')->trans(
                'teebb.core.menu.update_success', ['%value%' => $menu->getName()]
            ));

            return $this->redirectToRoute('teebb_menu_index');
        }
        return $this->render($this->templateRegistry->getTemplate('update', 'menu'), [
            'form' => $form->createView(),
            'menu' => $menu,
            'action' => 'update'
        ]);
    }

    /**
     * 删除菜单
     * @param Request $request
     * @param Menu $menu
     * @return Response
     */
    public function deleteAction(Request $request, Menu $menu)
    {
        $this->denyAccessUnlessGranted('menu_' . $menu->getMenuAlias() . '_delete');

        $form = $this->formContractor->generateDeleteForm($request->get('_route'), FormType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('_method')->getData() == 'DELETE') {
                //删除菜单项
                $menuRepo = $this->entityManager->getRepository(MenuItem::class);
                $menuItems = $menuRepo->findBy(['menu' => $menu]);
                foreach ($menuItems as $menuItem) {
                    $childrenItems = $menuRepo->children($menuItem);
                    foreach ($childrenItems as $childrenItem) {
                        $menuRepo->removeFromTree($childrenItem);
                    }
                    $this->entityManager->remove($menuItem);
                }
                //删除菜单
                $this->entityManager->remove($menu);
                $this->entityManager->flush();

                $this->addFlash('success', $this->container->get('translator')->trans(
                    'teebb.core.menu.delete_success', ['%value%' => $menu->getName()]
                ));

                return $this->redirectToRoute('teebb_menu_index');
            }
        }

        return $this->render($this->templateRegistry->getTemplate('delete', 'menu'), [
            'delete_form' => $form->createView(),
            'menu' => $menu,
            'action' => 'delete'
        ]);
    }

    /**
     * @param Request $request
     * @param Menu $menu
     * @return Response
     */
    public function manageMenuItemsAction(Request $request, Menu $menu)
    {
        $this->denyAccessUnlessGranted('menu_' . $menu->getMenuAlias() . '_item_manage');

        $typeRepo = $this->entityManager->getRepository(Types::class);
        $contentTypes = $typeRepo->findBy(['bundle' => 'content']);

        $contentRepo = $this->entityManager->getRepository(Content::class);
        $last_contents = $contentRepo->findBy([], null, 10);

        $taxonomyRepo = $this->entityManager->getRepository(Taxonomy::class);
        $taxonomies = $taxonomyRepo->findAll();

        $menuItemRepo = $this->entityManager->getRepository(MenuItem::class);
        $rootMenuItem = $menuItemRepo->findOneBy(['parent' => null, 'menu' => $menu]);

        return $this->render($this->templateRegistry->getTemplate('manage_menu_items', 'menu'), [
            'menu' => $menu,
            'root_menu_item' => $rootMenuItem,
            'content_types' => $contentTypes,
            'last_contents' => $last_contents,
            'taxonomies' => $taxonomies,
            'menu_item_repo' => $menuItemRepo,
            'action' => 'manage',
            'extra_assets' => ['nestable']
        ]);
    }

    /**
     * Ajax添加菜单项到菜单中
     * @param Request $request
     * @param Menu $menu
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function ajaxAddMenuItemAction(Request $request, Menu $menu)
    {
        $this->denyAccessUnlessGranted('menu_' . $menu->getMenuAlias() . '_item_add');

        $menus = $request->get('menus');

        $menuInfos = json_decode($menus);

        $rootMenuItem = $this->findMenuRootItem($menu);
        $childrenSize = $rootMenuItem->getChildren()->count();
        $priority = 0;
        foreach ($menuInfos as $menuInfo) {
            $menuInfo = (array)$menuInfo;

            $menuItem = new MenuItem();
            $menuItem->setMenu($menu);
            $menuItem->setMenuTitle($menuInfo['label']);
            $menuItem->setMenuTitleAttr($menuInfo['label']);
            $menuItem->setMenuLink($menuInfo['path']);
            $menuItem->setParent($rootMenuItem);
            $menuItem->setPriority($childrenSize + $priority);

            $this->entityManager->persist($menuItem);
            $priority++;
        }
        $this->entityManager->flush();

        return $this->json(null, 201, [], ['groups' => ['main']]);
    }

    /**
     * Ajax删除菜单项
     * @param Request $request
     * @param Menu $menu
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function ajaxRemoveMenuItemAction(Request $request, Menu $menu)
    {
        $this->denyAccessUnlessGranted('menu_' . $menu->getMenuAlias() . '_item_remove');

        $menuItemId = $request->get('menu-item-id');

        $menuItemRepo = $this->entityManager->getRepository(MenuItem::class);

        $menuItem = $menuItemRepo->find($menuItemId);

        $menuItemRepo->removeFromTree($menuItem);

        $this->entityManager->clear();

        return $this->json(null, 200);
    }


    /**
     * Ajax保存菜单项更改
     * @param Request $request
     * @param Menu $menu
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function ajaxSaveMenuInfoAction(Request $request, Menu $menu)
    {
        $this->denyAccessUnlessGranted('menu_' . $menu->getMenuAlias() . '_item_save');

        $menuName = $request->get('menu-name');
        if ($menuName !== $menu->getName()) {
            $menu->setName($menuName);
            $this->entityManager->persist($menu);
        }

        $menuInfos = json_decode($request->get('menu-items'));
        $menuRelations = json_decode($request->get('menu-item-relation'));

        $menuItemRepo = $this->entityManager->getRepository(MenuItem::class);
        $rootMenuItem = $menuItemRepo->findOneBy(['parent' => null, 'menu' => $menu]);

        $rootMenuItemPriority = 0;
        foreach ($menuRelations as $menuRelation) {
            $menuRelation = (array)$menuRelation;

            $menuItem = $menuItemRepo->find($menuRelation['id']);
            //每次变更需要重置第一层菜单项的父菜单项
            $menuItem->setParent($rootMenuItem);

            foreach ($menuInfos as $menuInfo) {
                $menuInfo = (array)$menuInfo;

                if ($menuInfo['id'] === $menuRelation['id']) {

                    $menuItem->setMenuLink($menuInfo['link']);
                    $menuItem->setMenuTitle($menuInfo['title']);
                    $menuItem->setMenuTitleAttr($menuInfo['title-attr']);
                    $menuItem->setPriority($rootMenuItemPriority);

                    $this->setMenuItemRelation($menuItem, $menuInfos, $menuRelation, $menuItemRepo);
                }

                $this->entityManager->persist($menuItem);
            }
            $rootMenuItemPriority++;
        }

        $this->entityManager->flush();

        //菜单项保存成功后，清除缓存
        $menuCacheEvent = new MenuCacheEvent();
        $menuCacheEvent->setMenuName($menu->getMenuAlias());
        $this->eventDispatcher->dispatch($menuCacheEvent, MenuCacheEvent::DELETE_MENU_CACHE);

        return $this->json(null, 200);
    }

    /**
     * 保存菜单项父子关系
     * @param MenuItem $menuItem
     * @param array $menuInfos
     * @param array $menuRelation
     * @param ObjectRepository $menuItemRepo
     */
    private function setMenuItemRelation(MenuItem $menuItem, array $menuInfos, array $menuRelation, ObjectRepository $menuItemRepo)
    {
        if (array_key_exists('children', $menuRelation)) {
            $childrenMenuItemPriority = 0;
            foreach ($menuRelation['children'] as $childMenuRelation) {
                $childMenuRelation = (array)$childMenuRelation;

                /**@var MenuItem $childMenuItem * */
                $childMenuItem = $menuItemRepo->find($childMenuRelation['id']);
                $childMenuItem->setParent($menuItem);
                $childMenuItem->setPriority($childrenMenuItemPriority);

                foreach ($menuInfos as $menuInfo) {
                    $menuInfo = (array)$menuInfo;
                    if ($menuInfo['id'] === $childMenuRelation['id']) {
                        $childMenuItem->setMenuLink($menuInfo['link']);
                        $childMenuItem->setMenuTitle($menuInfo['title']);
                        $childMenuItem->setMenuTitleAttr($menuInfo['title-attr']);
                    }
                }

                $this->entityManager->persist($childMenuItem);

                $this->setMenuItemRelation($childMenuItem, $menuInfos, $childMenuRelation, $menuItemRepo);
                $childrenMenuItemPriority++;
            }
        }

    }

    /**
     * 查询菜单根菜单项
     * @param Menu $menu
     * @return MenuItem|null
     */
    private function findMenuRootItem(Menu $menu)
    {
        $menuItemRepo = $this->entityManager->getRepository(MenuItem::class);
        return $menuItemRepo->findOneBy(['parent' => null, 'menu' => $menu]);
    }
}