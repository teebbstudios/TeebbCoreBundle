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
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Teebb\CoreBundle\AbstractService\EntityTypeInterface;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Entity\Types\ContentType;
use Teebb\CoreBundle\Event\AliasTransformEvent;
use Teebb\CoreBundle\Form\FormContractorInterface;
use Teebb\CoreBundle\Form\Type\AddFieldsType;
use Teebb\CoreBundle\Repository\RepositoryInterface;
use Teebb\CoreBundle\Templating\TemplateRegistry;
use Symfony\Component\HttpFoundation\Response;

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
        $paginator = $this->entityTypeRepository->createPaginator();
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
            $this->entityTypeService->getEntityTypeMetadata()->getFormSettings());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**@var ContentType $data * */
            $data = $form->getData();

            $repository = $this->entityTypeService->getRepository();
            $repository->save($data);

            //添加完类型，跳转到添加字段页面
            return $this->redirectToRoute($this->entityTypeService->getRouteName('add_field'), [
                'alias' => $this->aliasToNormal($data->getAlias())
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
     * 添加字段
     *
     * @param Request $request
     * @return Response
     */
    public function addFieldAction(Request $request)
    {
        $typeAlias = $this->aliasToMachine($request->get('alias'));

        $type = $this->entityTypeService->getEntityTypeMetadata()->getType();

        $form = $this->createForm(AddFieldsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $data = $form->getData();
            $fieldType = $data['select_fields'];
            $fieldLabel = $data['field_label'];
            $fieldAlias = $data['field_alias'];

            $fieldConfiguration = new FieldConfiguration();
            $fieldConfiguration->setType($type);
            $fieldConfiguration->setTypeAlias($typeAlias);
            $fieldConfiguration->setFieldLabel($fieldLabel);
            $fieldConfiguration->setFieldAlias($fieldAlias);
            $fieldConfiguration->setFieldType($fieldType);
            $fieldConfiguration->setDelta(0);

            $this->entityManager->persist($fieldConfiguration);
            $this->entityManager->flush();

            return $this->redirectToRoute(
                $this->entityTypeService->getRouteName('update_field'), [
                    'alias' => $this->aliasToNormal($typeAlias),
                    'fieldAlias' => $this->aliasToNormal($fieldAlias)
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
        $typeAlias = $this->aliasToMachine($request->get('alias'));

        $fieldConfigurationRepo = $this->entityManager->getRepository(FieldConfiguration::class);
        $fieldConfigurations = $fieldConfigurationRepo->getBySortableGroupsQuery(['typeAlias' => $typeAlias])->getResult();

        return $this->render($this->templateRegistry->getTemplate('list_fields', 'fields'), [
            'fields' => $fieldConfigurations,
            'action' => 'index_field'
        ]);
    }


    /**
     * 编辑字段
     *
     * @param Request $request
     * @return Response
     */
    public function updateFieldAction(Request $request)
    {
        $typeAlias = $this->aliasToMachine($request->get('alias'));
        $fieldAlias = $this->aliasToMachine($request->get('fieldAlias'));

        $fieldConfigurationRepo = $this->entityManager->getRepository(FieldConfiguration::class);
        $fieldConfiguration = $fieldConfigurationRepo->findOneBy(['typeAlias' => $typeAlias, 'fieldAlias' => $fieldAlias]);

//        $fieldConfigurationForm = $this->createForm();
        dd($fieldConfiguration);
    }


    /**
     * 机读别名下划线转成连字符
     * @param string $alias
     * @return string
     */
    private function aliasToNormal(string $alias): string
    {
        return str_replace('_', '-', $alias);
    }

    /**
     * 机读别名连字符转成下划线
     * @param string $alias
     * @return string
     */
    private function aliasToMachine(string $alias): string
    {
        return str_replace('-', '_', $alias);
    }
}