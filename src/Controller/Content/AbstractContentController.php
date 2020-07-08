<?php


namespace Teebb\CoreBundle\Controller\Content;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\AbstractService\EntityTypeInterface;
use Teebb\CoreBundle\Entity\BaseContent;
use Teebb\CoreBundle\Entity\Content;
use Teebb\CoreBundle\Entity\Fields\BaseFieldItem;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Form\Type\Content\ContentType;
use Teebb\CoreBundle\Listener\DynamicChangeFieldMetadataListener;
use Teebb\CoreBundle\Repository\Fields\FieldConfigurationRepository;
use Teebb\CoreBundle\Repository\Types\EntityTypeRepository;
use Teebb\CoreBundle\Templating\TemplateRegistry;
use Symfony\Component\HttpFoundation\Response;


/**
 * CRUD controller
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
abstract class AbstractContentController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var FieldConfigurationRepository
     */
    protected $fieldConfigRepository;

    /**
     * @var EntityTypeRepository
     */
    protected $typesRepository;
    /**
     * @var TemplateRegistry
     */
    private $templateRegistry;

    public function __construct(EntityManagerInterface $entityManager, TemplateRegistry $templateRegistry)
    {
        $this->entityManager = $entityManager;
        $this->fieldConfigRepository = $entityManager->getRepository(FieldConfiguration::class);
        $this->typesRepository = $entityManager->getRepository(Types::class);
        $this->templateRegistry = $templateRegistry;
    }

    /**
     * 创建内容首页，选择内容类型
     *
     * @param Request $request
     * @return Response
     */
    public function createIndexAction(Request $request)
    {
        $page = $request->get('page', 1);
        /**
         * @var Pagerfanta $paginator
         */
        $paginator = $this->typesRepository->createPaginator(['bundle' => 'types']);
        $paginator->setCurrentPage($page);

        return $this->render($this->templateRegistry->getTemplate('list_types', 'content'), [
            'data' => $paginator->getCurrentPageResults(),
            'action' => 'create_content'
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
        /**@var EntityTypeInterface $entityTypeService * */
        $entityTypeService = $this->container->get($request->get('_teebb_entity_type'));
        $data_class = $entityTypeService->getEntityClassName();

        $form = $this->createForm(ContentType::class,
            null,
            ['bundle' => $types->getBundle(), 'type_alias' => $types->getTypeAlias(), 'data_class' => $data_class]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //持久化内容和字段
            $this->persistContent($form, $types->getBundle(), $types->getTypeAlias(), $data_class);

            dd($form->getData()
//                $form->get('biao_ti')->getData(),
//                $form->get('wen_jian')->getData(),
//                $form->get('tu_xiang')->getData(),
//                $form->get('lian_jie')->getData(),
//                $form->get('you_jian')->getData()
            );

        }

        return $this->render($this->templateRegistry->getTemplate('create', 'content'), [
            'action' => 'create_content',
            'form' => $form->createView()
        ]);
    }

    /**
     * 持久化内容及所有字段数据
     * @param FormInterface $form
     * @param string $bundle 用于排序显示所有字段
     * @param string $typeAlias 内容类型的别名，用于获取当前内容类型的所有字段
     * @param string $contentClassName 内容Entity全类名，用于动态修改字段映射
     */
    protected function persistContent(FormInterface $form, string $bundle, string $typeAlias, string $contentClassName)
    {
        //内容Entity object
        $baseContent = $form->getData();

        $this->entityManager->persist($baseContent);

        //获取当前内容类型所有字段
        $fields = $this->fieldConfigRepository
            ->getBySortableGroupsQueryDesc(['bundle' => $bundle, 'typeAlias' => $typeAlias])->getResult();

        //doctrine Event manager
        $evm = $this->entityManager->getEventManager();

        /**@var FieldConfiguration $field * */
        foreach ($fields as $field) {
            //获取当前字段的所有表单数据
            $fieldDataArray = $form->get($field->getFieldAlias())->getData();
            if (!empty($fieldDataArray)) {
                //动态修改字段entity的mapping
                $dynamicChangeFieldMetadataListener = new DynamicChangeFieldMetadataListener($field, $contentClassName);
                $evm->addEventListener(Events::loadClassMetadata, $dynamicChangeFieldMetadataListener);

                /**@var BaseFieldItem $dataItem * */
                foreach ($fieldDataArray as $index => $dataItem) {
                    //处理字段和内容Entity的关系
                    $dataItem->setEntity($baseContent);

                    $this->entityManager->persist($dataItem);
                }
                //移除doctrine监听器
                $evm->removeEventListener(Events::loadClassMetadata, $dynamicChangeFieldMetadataListener);
            }
        }

        $this->entityManager->flush();
    }
}