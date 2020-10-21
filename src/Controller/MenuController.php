<?php


namespace Teebb\CoreBundle\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\Entity\Content;
use Teebb\CoreBundle\Entity\Menu;
use Teebb\CoreBundle\Entity\MenuItem;
use Teebb\CoreBundle\Entity\Taxonomy;
use Teebb\CoreBundle\Entity\Types\Types;
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

    public function __construct(EntityManagerInterface $entityManager, TemplateRegistry $templateRegistry,
                                FormContractorInterface $formContractor)
    {
        $this->templateRegistry = $templateRegistry;
        $this->entityManager = $entityManager;
        $this->formContractor = $formContractor;
    }

    public function indexAction(Request $request)
    {
//        $this->denyAccessUnlessGranted('menu_index');

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
//        $this->denyAccessUnlessGranted('menu_create');

        $form = $this->createForm(MenuType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**@var Menu $menu * */
            $menu = $form->getData();
            $this->entityManager->persist($menu);
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
//        $this->denyAccessUnlessGranted('menu_update');

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
//        $this->denyAccessUnlessGranted('menu_delete');

        $form = $this->formContractor->generateDeleteForm($request->get('_route'), FormType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('_method')->getData() == 'DELETE') {
                //删除菜单项
                $menuRepo = $this->entityManager->getRepository(MenuItem::class);
                $menuItems = $menuRepo->findBy(['menu'=>$menu]);
                foreach ($menuItems as $menuItem)
                {
                    $menuRepo->removeFromTree($menuItem);
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
//        dd($request);
        $typeRepo = $this->entityManager->getRepository(Types::class);
        $contentTypes = $typeRepo->findBy(['bundle' => 'content']);

        $contentRepo = $this->entityManager->getRepository(Content::class);
        $last_contents = $contentRepo->findBy([], null, 10);

        $taxonomyRepo = $this->entityManager->getRepository(Taxonomy::class);
        $taxonomies = $taxonomyRepo->findAll();

        $menuRepo = $this->entityManager->getRepository(MenuItem::class);
        $menuItems = $menuRepo->findBy(['parent' => null]);

        return $this->render($this->templateRegistry->getTemplate('manage_menu_items', 'menu'), [
            'menu' => $menu,
            'menu_items' => $menuItems,
            'content_types' => $contentTypes,
            'last_contents' => $last_contents,
            'taxonomies' => $taxonomies,
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
        $menus = $request->get('menus');

        $menuInfos = json_decode($menus);

        $menuItems = [];
        foreach ($menuInfos as $menuInfo) {
            $menuInfo = (array)$menuInfo;

            $menuItem = new MenuItem();
            $menuItem->setMenu($menu);
            $menuItem->setMenuTitle($menuInfo['label']);
            $menuItem->setMenuLink($menuInfo['path']);

            $menuItems[] = $menuItem;

            $this->entityManager->persist($menuItem);
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
    public function ajaxRemoveMenuItemAction(Request $request)
    {
        $menuItemId = $request->get('menu-item-id');

        $menuItemRepo = $this->entityManager->getRepository(MenuItem::class);

        $menuItem = $menuItemRepo->find($menuItemId);

        $menuItemRepo->removeFromTree($menuItem);

        $this->entityManager->clear();

        return $this->json(null,200);
    }


}