<?php


namespace Teebb\CoreBundle\Controller\Content;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\Entity\BaseContent;
use Teebb\CoreBundle\Entity\Content;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Form\Type\Content\ContentBatchOptionsType;
use Teebb\CoreBundle\Form\Type\ContentType;
use Teebb\CoreBundle\Listener\DynamicChangeFieldMetadataListener;
use Teebb\CoreBundle\Repository\Fields\FieldConfigurationRepository;
use Teebb\CoreBundle\Repository\Types\EntityTypeRepository;
use Teebb\CoreBundle\Templating\TemplateRegistry;
use Symfony\Component\HttpFoundation\Response;


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

        $baseContentRepository = $this->entityManager->getRepository($entityTypeService->getEntityClassName());
        /**
         * @var Pagerfanta $paginator
         */
        $paginator = $baseContentRepository->createPaginator(['bundle' => $entityTypeService->getBundle()]);
        $paginator->setCurrentPage($page);

        $batchActionForm = $this->createForm(ContentBatchOptionsType::class);
        $batchActionForm->handleRequest($request);
        if ($batchActionForm->isSubmitted() && $batchActionForm->isValid()){
            $data = $batchActionForm->getData();
            dd($data, $request);
        }
        return $this->render($this->templateRegistry->getTemplate('index', 'content'), [
            'data' => $paginator->getCurrentPageResults(),
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
     * @return Response
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
            $this->persistSubstance($form, $types->getBundle(), $types->getTypeAlias(), $data_class);
        }

        return $this->render($this->templateRegistry->getTemplate('create', 'content'), [
            'action' => 'create',
            'form' => $form->createView(),
            'entity_type' => $entityTypeService
        ]);
    }


    /**
     * 更新内容
     *
     * @param Request $request
     * @param Content $content
     * @return Response
     */
    public function updateAction(Request $request, Content $content)
    {
        $entityTypeService = $this->getEntityTypeService($request);

        $data_class = $entityTypeService->getEntityClassName();
        $entityFormType = $entityTypeService->getEntityFormType();

        $form = $this->createForm($entityFormType, null,
            [
                'bundle' => $entityTypeService->getBundle(),
                'type_alias' => $content->getType(), '
                data_class' => $data_class
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //持久化内容和字段
            $this->persistSubstance($form, $entityTypeService->getBundle(), $content->getType(), $data_class);
        }

        return $this->render($this->templateRegistry->getTemplate('create', 'content'), [
            'action' => 'create',
            'form' => $form->createView(),
            'entity_type' => $entityTypeService
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