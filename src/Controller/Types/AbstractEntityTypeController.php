<?php
/**
 * This file is part of the TeebbCoreBundle package.
 *
 * Author: Quan Weiwei <qww.zone@gmail.com>
 * Date: 2020/5/20
 * (c) teebbstudios
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Teebb\CoreBundle\Controller\Types;

use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Teebb\CoreBundle\AbstractService\EntityTypeInterface;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Entity\Types\TypeInterface;
use Teebb\CoreBundle\Form\FormContractorInterface;
use Teebb\CoreBundle\Form\Type\AddFieldsType;
use Teebb\CoreBundle\Form\Type\FieldConfigurationType;
use Teebb\CoreBundle\Repository\Fields\FieldConfigurationRepository;
use Teebb\CoreBundle\Repository\RepositoryInterface;
use Teebb\CoreBundle\Templating\TemplateRegistry;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;

/**
 * 内容实体类型EntityType的Controller
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class AbstractEntityTypeController extends AbstractController
{
    /**
     * @var EntityTypeInterface
     */
    protected $entityTypeService;

    /**
     * @var TemplateRegistry
     */
    protected $templateRegistry;

    /**
     * @var RepositoryInterface
     */
    protected $entityTypeRepository;

    /**
     * @var FormContractorInterface
     */
    private $formContractor;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var FieldConfigurationRepository
     */
    private $fieldConfigurationRepository;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TemplateRegistry $templateRegistry, EntityManagerInterface $entityManager,
                                EventDispatcherInterface $dispatcher,
                                FormContractorInterface $formContractor)
    {
        $this->templateRegistry = $templateRegistry;
        $this->formContractor = $formContractor;
        $this->entityManager = $entityManager;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->container->get('request_stack')->getCurrentRequest();
    }

    /**
     * 读取request参数_teebb_entity_type获取EntityTypeService
     *
     * @internal
     * @required
     */
    public function configure()
    {
        $request = $this->getRequest();

        $entityTypeServiceId = $request->get('_teebb_entity_type');

        if (!$entityTypeServiceId) {
            throw new \RuntimeException(sprintf(
                'There is no `_teebb_entity_type` defined for the controller `%s` and the current route `%s`',
                static::class,
                $request->get('_route')
            ));
        }

        $this->entityTypeService = $this->container->get($entityTypeServiceId);

        if (!$this->entityTypeService) {
            throw new \RuntimeException(sprintf(
                'Unable to find the entity type service class related to the current controller (%s)',
                static::class
            ));
        }

        $this->entityTypeRepository = $this->entityTypeService->getRepository();

        if (!$this->entityTypeService) {
            throw new \RuntimeException(sprintf(
                'Unable to find the entity type repository class related to the current controller (%s)',
                static::class
            ));
        }

        //将此entityTypeService添加到twig全局变量
        $twig = $this->container->get('twig');
        $twig->addGlobal('entity_type', $this->entityTypeService);

        $this->fieldConfigurationRepository = $this->getFieldConfigurationRepository();

        $this->translator = $this->container->get('translator');
    }

    /**
     * @return TemplateRegistry
     */
    public function getTemplateRegistry(): TemplateRegistry
    {
        return $this->templateRegistry;
    }

    /**
     * @param TemplateRegistry $templateRegistry
     */
    public function setTemplateRegistry(TemplateRegistry $templateRegistry): void
    {
        $this->templateRegistry = $templateRegistry;
    }

    /**
     * 显示不同内容实体类型EntityType列表
     *
     * @param Request $request
     * @return Response
     * @todo 添加EventListener统一处理用户权限问题
     *
     */
    public function indexAction(Request $request)
    {
        $page = $request->get('page', 1);
        /**
         * @var Pagerfanta $paginator
         */
        $paginator = $this->entityTypeRepository->createPaginator(['bundle' => $this->entityTypeService->getBundle()]);
        $paginator->setCurrentPage($page);

        return $this->render($this->templateRegistry->getTemplate('list', 'types'), [
            'label' => $this->entityTypeService->getEntityTypeMetadata()->getLabel(),
            'action' => $request->get('_teebb_action'),
            'data' => $paginator->getCurrentPageResults(),
            'buttons' => $this->entityTypeService->getActionButtons()
        ]);
    }

    /**
     * 创建内容实体类型EntityType
     *
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $formName = $request->get('_route');

        $entityClass = $this->entityTypeService->getEntityClass();

        $formBuilder = $this->formContractor->getFormBuilder($formName, FormType::class, null, ['data_class' => $entityClass]);

        $form = $this->formContractor->buildEntityTypeForm($formBuilder, $entityClass,
            $this->entityTypeService->getEntityTypeMetadata()->getFormSettings(), $this->entityTypeService->getBundle());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**@var TypeInterface $data * */
            $data = $form->getData();

            $repository = $this->entityTypeService->getRepository();
            $repository->save($data);

            $this->addFlash('success', $this->translator->trans(
                'teebb.core.entity_type.create_success', ['%value%' => $data->getLabel()], 'TeebbCoreBundle'
            ));

            //添加完类型，跳转到添加字段页面
            return $this->redirectToRoute($this->entityTypeService->getRouteName('add_field'), [
                'typeAlias' => $data->getTypeAlias()
            ]);
        }

        return $this->render($this->templateRegistry->getTemplate('create', 'types'), [
            'label' => $this->entityTypeService->getEntityTypeMetadata()->getLabel(),
            'action' => $request->get('_teebb_action'),
            'buttons' => $this->entityTypeService->getActionButtons(),
            'form' => $form->createView(),
            'extra_assets' => ['transliteration'], //当前页面需要额外添加的assets库
        ]);
    }

    /**
     * 更新内容类型
     *
     * @param Request $request
     * @return Response
     */
    public function updateAction(Request $request)
    {
        $typeAlias = $request->get('typeAlias');

        $entityTypeRepo = $this->entityTypeService->getRepository();

        $entityType = $entityTypeRepo->findOneBy(['typeAlias' => $typeAlias]);

        if (null === $entityType) {
            throw new NotFoundHttpException(sprintf('Url path wrong!!! Check the parameter "%s"', $request->get('typeAlias')));
        }

        $entityClass = $this->entityTypeService->getEntityClass();

        $formName = $request->get('_route');
        $formBuilder = $this->formContractor->getFormBuilder($formName, FormType::class, $entityType, ['data_class' => $entityClass]);

        $form = $this->formContractor->buildEntityTypeForm($formBuilder, $entityClass,
            $this->entityTypeService->getEntityTypeMetadata()->getFormSettings(), $this->entityTypeService->getBundle());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**@var TypeInterface $data * */
            $data = $form->getData();
            $entityTypeRepo->save($data);

            $this->addFlash('success', $this->translator->trans(
                'teebb.core.entity_type.update_success', ['%value%' => $data->getLabel()], 'TeebbCoreBundle'
            ));

            //添加完类型，跳转到列表页面
            return $this->redirectToRoute($this->entityTypeService->getRouteName('index'), [
                'typeAlias' => $data->getTypeAlias()
            ]);
        }

        return $this->render($this->templateRegistry->getTemplate('update', 'types'), [
            'label' => $this->entityTypeService->getEntityTypeMetadata()->getLabel(),
            'action' => $request->get('_teebb_action'),
            'buttons' => $this->entityTypeService->getActionButtons(),
            'form' => $form->createView(),
            'extra_assets' => ['transliteration'], //当前页面需要额外添加的assets库
        ]);

    }

    /**
     * 添加字段
     *
     * @param Request $request
     * @return Response
     */
    public function addFieldAction(Request $request)
    {
        $typeAlias = $request->get('typeAlias');

        $this->checkTypeObjectExist($typeAlias);

        $bundle = $this->entityTypeService->getEntityTypeMetadata()->getBundle();

        $form = $this->createForm(AddFieldsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $data = $form->getData();
            $fieldType = $data['select_fields'];
            $fieldLabel = $data['field_label'];
            $fieldAlias = $data['field_alias'];

            $fieldConfiguration = new FieldConfiguration();
            $fieldConfiguration->setBundle($bundle);
            $fieldConfiguration->setTypeAlias($typeAlias);
            $fieldConfiguration->setFieldLabel($fieldLabel);
            $fieldConfiguration->setFieldAlias($fieldAlias);
            $fieldConfiguration->setFieldType($fieldType);
            $fieldConfiguration->setDelta(0);

            $this->entityManager->persist($fieldConfiguration);
            $this->entityManager->flush();

            $this->addFlash('success', $this->translator->trans(
                'teebb.core.field.create_success', ['%value%' => $fieldLabel], 'TeebbCoreBundle'
            ));

            return $this->redirectToRoute(
                $this->entityTypeService->getRouteName('update_field'), [
                    'typeAlias' => $typeAlias,
                    'fieldAlias' => $fieldAlias
                ]
            );
        }

        return $this->render($this->templateRegistry->getTemplate('select_fields', 'fields'), [
            'action' => 'add_field',
            'form' => $form->createView(),
            'extra_assets' => ['transliteration'], //当前页面需要额外添加的assets库
        ]);
    }

    /**
     * 管理当前类型所有字段
     *
     * @param Request $request
     * @return Response
     */
    public function indexFieldAction(Request $request)
    {
        $typeAlias = $request->get('typeAlias');

        $this->checkTypeObjectExist($typeAlias);

        $fieldConfigurations = $this->fieldConfigurationRepository
            ->getBySortableGroupsQuery(['typeAlias' => $typeAlias])
            ->getResult();

        return $this->render($this->templateRegistry->getTemplate('list_fields', 'fields'), [
            'fields' => $fieldConfigurations,
            'action' => 'index_field'
        ]);
    }


    /**
     * @param Request $request
     * @return Response
     */
    public function updateFieldAction(Request $request)
    {
        $typeAlias = $request->get('typeAlias');
        $fieldAlias = $request->get('fieldAlias');

        $this->checkTypeObjectExist($typeAlias);

        /**@var FieldConfiguration $fieldConfiguration * */
        $fieldConfiguration = $this->fieldConfigurationRepository->findOneBy(['typeAlias' => $typeAlias, 'fieldAlias' => $fieldAlias]);

        $form = $this->createForm(FieldConfigurationType::class, $fieldConfiguration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fieldConfiguration = $form->getData();
            //clone一下，否则doctrine认为没有修改object，从而persist失败
            $newSettings = clone $fieldConfiguration->getSettings();

            $fieldConfiguration->setSettings($newSettings);
            $this->entityManager->persist($fieldConfiguration);
            $this->entityManager->flush();
        }

        return $this->render($this->templateRegistry->getTemplate('update_field', 'fields'), [
            'action' => 'update_field',
            'form' => $form->createView(),
            'fieldConfig' => $fieldConfiguration
        ]);

    }

    /**
     * 获取字段配置Repository
     *
     * @return FieldConfigurationRepository
     */
    protected function getFieldConfigurationRepository()
    {
        return $this->entityManager->getRepository(FieldConfiguration::class);
    }

    /**
     * 检查URL参数中类型object是否存在
     * @param string $typeAlias
     */
    protected function checkTypeObjectExist(string $typeAlias)
    {
        //检测URL中类型实体Types是否存在，如果不存在报404
        if (null === $this->entityTypeService->getRepository()->findOneBy(['typeAlias' => $typeAlias])) {
            throw new NotFoundHttpException(sprintf('Url path wrong!!! Check the parameter "%s"', $typeAlias));
        }
    }
}