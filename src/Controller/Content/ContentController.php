<?php


namespace Teebb\CoreBundle\Controller\Content;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\Entity\BaseContent;
use Teebb\CoreBundle\Entity\Content;
use Teebb\CoreBundle\Entity\Fields\BooleanItem;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Form\Type\Content\ContentBatchOptionsType;
use Teebb\CoreBundle\Form\Type\ContentType;
use Teebb\CoreBundle\Listener\DynamicChangeFieldMetadataListener;
use Teebb\CoreBundle\Repository\Fields\FieldConfigurationRepository;
use Teebb\CoreBundle\Repository\Types\EntityTypeRepository;
use Teebb\CoreBundle\Templating\TemplateRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;


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
            dd($data, $request);
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
            //持久化内容和字段
            /**@var Content $content * */
            $content = $this->persistSubstance($form, $types->getBundle(), $types->getTypeAlias(), $data_class);

            $this->addFlash('success', $this->container->get('translator')->trans(
                'teebb.core.content.create_success', ['%value%' => $content->getTitle()]
            ));

            //内容添加完成，跳转到内容列表页
            return $this->redirectToRoute('teebb_content_index');
        }

        return $this->render($this->templateRegistry->getTemplate('create', 'content'), [
            'action' => 'create',
            'form' => $form->createView(),
            'entity_type' => $entityTypeService,
            'type_alias' => $types->getTypeAlias()
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

            //持久化内容和字段
            $this->persistSubstance($form, $entityTypeService->getBundle(), $content->getTypeAlias(), $data_class);

            $this->addFlash('success', $this->container->get('translator')->trans(
                'teebb.core.content.update_success', ['%value%' => $content->getTitle()]
            ));

            //内容更新完成，跳转到内容列表页
            return $this->redirectToRoute('teebb_content_index');
        }

        return $this->render($this->templateRegistry->getTemplate('update', 'content'), [
            'action' => 'update',
            'form' => $form->createView(),
            'entity_type' => $entityTypeService,
            'subject' => $content,
            'type_alias' => $content->getTypeAlias()
        ]);
    }

    /**
     * 删除内容
     *
     * @param Request $request
     * @param Content $content
     * @return Response
     */
    public function deleteAction(Request $request, Content $content)
    {
        // TODO: Implement deleteAction() method.
    }


}