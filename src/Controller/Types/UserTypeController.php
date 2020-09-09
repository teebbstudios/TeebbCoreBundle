<?php


namespace Teebb\CoreBundle\Controller\Types;

use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\Entity\Group;
use Teebb\CoreBundle\Entity\User;
use Teebb\CoreBundle\Form\Type\Content\UserType;
use Teebb\CoreBundle\Form\Type\User\GroupType;
use Teebb\CoreBundle\Repository\GroupRepository;
use Teebb\CoreBundle\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * 用户类型Controller
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class UserTypeController extends AbstractEntityTypeController
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var GroupRepository
     */
    private $groupRepository;

    public function configure()
    {
        parent::configure();

        $this->userRepository = $this->entityManager->getRepository(User::class);
        $this->groupRepository = $this->entityManager->getRepository(Group::class);
    }

    /**
     * 管理所有用户
     * @param Request $request
     * @return Response
     */
    public function peopleIndexAction(Request $request)
    {
        $this->checkActionPermission($request);

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);

        /**
         * @var Pagerfanta $paginator
         */
        $paginator = $this->userRepository->createPaginator();
        $paginator->setMaxPerPage($limit);
        $paginator->setCurrentPage($page);

        return $this->render($this->templateRegistry->getTemplate('people_index', 'user'), [
            'paginator' => $paginator,
            'action' => 'people_index',
        ]);
    }

    /**
     * 管理所有用户
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function peopleUpdateAction(Request $request, User $user)
    {
        $this->checkActionPermission($request);

        $userForm = $this->createForm(UserType::class, $user, [
                'bundle' => 'user',
                'type_alias' => 'people',
                'data_class' => User::class
            ]
        );
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid())
        {
            try {
                //持久化用户和字段
                /**@var User $user * */
                $user = $this->persistSubstance($this->entityManager, $this->fieldConfigurationRepository,
                    $userForm, 'user', 'people', User::class);

                $this->addFlash('success', $this->container->get('translator')->trans(
                    'teebb.core.user.update_success', ['%value%' => $user->getUsername()]
                ));

                //用户更新完成，跳转到列表页
                return $this->redirectToRoute('user_people_index');

            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->render($this->templateRegistry->getTemplate('people_update', 'user'), [
            'action' => 'people_update',
            'user_form' => $userForm->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function groupIndexAction(Request $request)
    {
        $this->checkActionPermission($request);

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);

        /**
         * @var Pagerfanta $paginator
         */
        $paginator = $this->groupRepository->createPaginator();
        $paginator->setMaxPerPage($limit);
        $paginator->setCurrentPage($page);

        return $this->render($this->templateRegistry->getTemplate('group_index', 'user'), [
            'paginator' => $paginator,
            'action' => 'group_index',
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function groupCreateAction(Request $request)
    {
        $this->checkActionPermission($request);

        $groupForm = $this->createForm(GroupType::class);

        $groupForm->handleRequest($request);

        if ($groupForm->isSubmitted() && $groupForm->isValid()) {
            /**@var Group $group * */
            $group = $groupForm->getData();
            $this->entityManager->persist($group);
            $this->entityManager->flush();

            $this->addFlash('success', $this->container->get('translator')->trans(
                'teebb.core.group.create_success', ['%group%' => $group->getName()]
            ));

            return $this->redirectToRoute('user_group_index');
        }

        return $this->render($this->templateRegistry->getTemplate('group_form', 'user'), [
            'action' => 'group_create',
            'group_form' => $groupForm->createView(),
            'extra_assets' => ['transliteration'], //当前页面需要额外添加的assets库
        ]);
    }

    /**
     * @param Request $request
     * @param Group $group
     * @return Response
     */
    public function groupUpdateAction(Request $request, Group $group)
    {
        $this->checkActionPermission($request);

        $groupForm = $this->createForm(GroupType::class, $group);

        $groupForm->handleRequest($request);

        if ($groupForm->isSubmitted() && $groupForm->isValid()) {
            /**@var Group $group * */
            $group = $groupForm->getData();
            $this->entityManager->persist($group);
            $this->entityManager->flush();

            $this->addFlash('success', $this->container->get('translator')->trans(
                'teebb.core.group.update_success', ['%group%' => $group->getName()]
            ));

            return $this->redirectToRoute('user_group_index');
        }

        return $this->render($this->templateRegistry->getTemplate('group_form', 'user'), [
            'action' => 'group_update',
            'group_form' => $groupForm->createView(),
        ]);
    }


    /**
     * @param Request $request
     * @param Group $group
     * @return Response
     */
    public function groupDeleteAction(Request $request, Group $group)
    {
        $this->checkActionPermission($request);

        $deleteForm = $this->formContractor->generateDeleteForm('delete_group', FormType::class, $group);

        $deleteForm->handleRequest($request);

        if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {

            if ($deleteForm->get('_method')->getData() == 'DELETE') {
                $this->entityManager->remove($group);
                $this->entityManager->flush();

                $this->addFlash('success', $this->container->get('translator')->trans(
                    'teebb.core.group.delete_success', ['%group%' => $group->getName()]
                ));

                return $this->redirectToRoute('user_group_index');
            }

        }

        return $this->render($this->templateRegistry->getTemplate('group_delete', 'user'), [
            'action' => 'group_delete',
            'delete_form' => $deleteForm->createView(),
            'group' => $group
        ]);
    }

}