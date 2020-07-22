<?php


namespace Teebb\CoreBundle\Controller\Content;


use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\Entity\Content;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Form\Type\Content\ContentBatchOptionsType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Teebb\CoreBundle\Repository\BaseContentRepository;


/**
 * 内容entity controller
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class ContentController extends AbstractContentController
{
    /**
     * 显示所有内容
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $entityTypeService = $this->getEntityTypeService($request);

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);

        //Todo: 此处添加内容过滤器，可写一个单独bundle，用于生成内容过滤器表单，表单的提交生成过滤条件数组，应用到下方查询。
        //Todo: 参考SonataAdmin 及 Sylius, 暂留空。下个大版本增加吧。

        /**@var BaseContentRepository $baseContentRepository * */
        $baseContentRepository = $this->entityManager->getRepository($entityTypeService->getEntityClassName());

        /**
         * @var Pagerfanta $paginator
         */
        $paginator = $baseContentRepository->createPaginator(['bundle' => $entityTypeService->getBundle()], ['id' => 'DESC']);
        $paginator->setMaxPerPage($limit);
        $paginator->setCurrentPage($page);

        $batchActionForm = $this->createForm(ContentBatchOptionsType::class);
        $batchActionForm->handleRequest($request);
        if ($batchActionForm->isSubmitted() && $batchActionForm->isValid()) {
            $data = $batchActionForm->getData();
            $contentIds = $request->get('content');

            /**@var Content[] $contents * */
            $contents = $baseContentRepository->getBatchContentItems(Content::class, $contentIds);

            //批量操作
            switch ($data['batch']) {
                case 'batch_delete':
                    foreach ($contents as $content) {
                        $this->entityManager->remove($content);
                    }
                    break;
                case 'batch_unpublish':
                    foreach ($contents as $content) {
                        $content->setStatus('draft');
                        $this->entityManager->persist($content);
                    }
                    break;
                case 'batch_publish':
                    foreach ($contents as $content) {
                        $content->setStatus('publish');
                        $this->entityManager->persist($content);
                    }
                    break;
            }
            $this->entityManager->flush();

            $batchAction = $this->container->get('translator')->trans($data['batch']);
            $this->addFlash('success', $this->container->get('translator')->trans(
                'teebb.core.content.batch_action_success', ['%action%' => $batchAction]
            ));

        }
        return $this->render($this->templateRegistry->getTemplate('index', 'content'), [
            'paginator' => $paginator,
            'action' => 'index',
            'entity_type' => $entityTypeService,
            'batch_action_form' => $batchActionForm->createView()
        ]);
    }

    /**
     * 创建内容首页，选择内容类型
     *
     * @param Request $request
     * @return Response
     */
    public function createIndexAction(Request $request)
    {
        $entityTypeService = $this->getEntityTypeService($request);

        $page = $request->get('page', 1);
        /**
         * @var Pagerfanta $paginator
         */
        $paginator = $this->typesRepository->createPaginator(['bundle' => $entityTypeService->getBundle()]);
        $paginator->setCurrentPage($page);

        return $this->render($this->templateRegistry->getTemplate('list_types', 'content'), [
            'data' => $paginator->getCurrentPageResults(),
            'action' => 'create',
            'entity_type' => $entityTypeService
        ]);
    }

    /**
     * 创建内容
     *
     * @param Request $request
     * @param Types $types
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function createAction(Request $request, Types $types)
    {
        $entityTypeService = $this->getEntityTypeService($request);

        $data_class = $entityTypeService->getEntityClassName();
        $entityFormType = $entityTypeService->getEntityFormType();

        $form = $this->createForm($entityFormType,
            null,
            ['bundle' => $types->getBundle(), 'type_alias' => $types->getTypeAlias(), 'data_class' => $data_class]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                //持久化内容和字段
                /**@var Content $content * */
//                $content = $this->persistSubstance($form, $types->getBundle(), $types->getTypeAlias(), $data_class);
                $content = $this->persistSubstance($this->entityManager, $this->fieldConfigRepository,
                    $form, $types->getBundle(), $types->getTypeAlias(), $data_class);

                $this->addFlash('success', $this->container->get('translator')->trans(
                    'teebb.core.content.create_success', ['%value%' => $content->getTitle()]
                ));

                //内容添加完成，跳转到内容列表页
                return $this->redirectToRoute('teebb_content_index');

            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }

        }

        return $this->render($this->templateRegistry->getTemplate('create', 'content'), [
            'action' => 'create',
            'form' => $form->createView(),
            'entity_type' => $entityTypeService,
            'type_alias' => $types->getTypeAlias(),
            'extra_assets' => ['autocompletejs'], //当前页面需要额外添加的assets库
        ]);
    }


    /**
     * 更新内容
     *
     * @param Request $request
     * @param Content $content
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function updateAction(Request $request, Content $content)
    {
        $entityTypeService = $this->getEntityTypeService($request);

        $data_class = $entityTypeService->getEntityClassName();
        $entityFormType = $entityTypeService->getEntityFormType();

        $form = $this->createForm($entityFormType, $content,
            [
                'bundle' => $entityTypeService->getBundle(),
                'type_alias' => $content->getTypeAlias(),
                'data_class' => $data_class
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                //持久化内容和字段
                $this->persistSubstance($this->entityManager, $this->fieldConfigRepository,
                    $form, $entityTypeService->getBundle(), $content->getTypeAlias(), $data_class, $content);

                $this->addFlash('success', $this->container->get('translator')->trans(
                    'teebb.core.content.update_success', ['%value%' => $content->getTitle()]
                ));

                //内容更新完成，跳转到内容列表页
                return $this->redirectToRoute('teebb_content_index');

            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }

        }

        return $this->render($this->templateRegistry->getTemplate('update', 'content'), [
            'action' => 'update',
            'form' => $form->createView(),
            'entity_type' => $entityTypeService,
            'subject' => $content,
            'type_alias' => $content->getTypeAlias(),
            'extra_assets' => ['autocompletejs'], //当前页面需要额外添加的assets库
        ]);
    }

    /**
     * 删除内容
     *
     * @param Request $request
     * @param Content $content
     * @return Response
     * @throws \Exception
     */
    public function deleteAction(Request $request, Content $content)
    {
        $entityTypeService = $this->getEntityTypeService($request);

        $deleteForm = $this->formContractor->generateDeleteForm($request->attributes->get('_route'), FormType::class, $content);

        $deleteForm->handleRequest($request);

        if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {
            if ($deleteForm->get('_method')->getData() === 'DELETE') {
                try {
//                    $this->deleteSubstance($entityTypeService->getBundle(), $content->getTypeAlias(), $content);
                    $this->deleteSubstance($this->entityManager, $this->fieldConfigRepository, $this->container, $entityTypeService->getBundle(), $content->getTypeAlias(), $content);

                    $this->addFlash('success', $this->container->get('translator')->trans(
                        'teebb.core.content.delete_success', ['%value%' => $content->getTitle()]
                    ));

                    //内容更新完成，跳转到内容列表页
                    return $this->redirectToRoute('teebb_content_index');

                } catch (\Exception $e) {
                    $this->addFlash('danger', $e->getMessage());
                }
            }
        }

        return $this->render($this->templateRegistry->getTemplate('delete', 'content'), [
            'action' => 'delete',
            'delete_form' => $deleteForm->createView(),
            'entity_type' => $entityTypeService,
            'subject' => $content,
            'type_alias' => $content->getTypeAlias()
        ]);
    }

}