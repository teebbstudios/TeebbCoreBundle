<?php


namespace Teebb\CoreBundle\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\Entity\Content;
use Teebb\CoreBundle\Entity\Menu;
use Teebb\CoreBundle\Entity\Taxonomy;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Form\FormContractorInterface;
use Teebb\CoreBundle\Form\Type\FormatterType;
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

            if ($form->get('_method')->getData() == 'DELETE'){
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
        $typeRepo = $this->entityManager->getRepository(Types::class);
        $contentTypes = $typeRepo->findBy(['bundle' => 'content']);

        $contentRepo = $this->entityManager->getRepository(Content::class);
        $last_contents = $contentRepo->findBy([],null,10);

        $taxonomyRepo = $this->entityManager->getRepository(Taxonomy::class);
        $taxonomies = $taxonomyRepo->findAll();

        return $this->render($this->templateRegistry->getTemplate('manage_menu_items', 'menu'), [
            'menu' => $menu,
            'content_types' => $contentTypes,
            'last_contents' => $last_contents,
            'taxonomies' => $taxonomies,
            'action' => 'manage',
            'extra_assets' => ['nestable']
        ]);
    }
}