<?php


namespace Teebb\CoreBundle\Controller\Content;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\AbstractService\EntityTypeInterface;
use Teebb\CoreBundle\Controller\SubstanceDBALOptionsTrait;
use Teebb\CoreBundle\Entity\Content;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Form\FormContractorInterface;
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
    use SubstanceDBALOptionsTrait;

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
    protected $templateRegistry;

    /**
     * @var FormContractorInterface
     */
    protected $formContractor;

    public function __construct(EntityManagerInterface $entityManager, TemplateRegistry $templateRegistry,
                                FormContractorInterface $formContractor)
    {
        $this->entityManager = $entityManager;
        $this->fieldConfigRepository = $entityManager->getRepository(FieldConfiguration::class);
        $this->typesRepository = $entityManager->getRepository(Types::class);
        $this->templateRegistry = $templateRegistry;
        $this->formContractor = $formContractor;
    }

    /**
     * 显示所有内容
     * @param Request $request
     * @return Response
     */
    abstract public function indexAction(Request $request);

    /**
     * 创建内容
     *
     * @param Request $request
     * @param Types $types
     * @return Response
     */
    abstract public function createAction(Request $request, Types $types);


    /**
     * 更新内容
     *
     * @param Request $request
     * @param Content $content
     * @return Response
     */
    abstract public function updateAction(Request $request, Content $content);

    /**
     * 删除内容
     *
     * @param Request $request
     * @param Content $content
     * @return Response
     */
    abstract public function deleteAction(Request $request, Content $content);

    /**
     * 用户引用内容、分类字段autocomplete
     */
    public function getSubstancesApi(Request $request)
    {
        $entityClass = $request->get('entity_class');
        $queryLabel = $request->get('query_label');
        $query = $request->get('query');

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('c')->from($entityClass, 'c')
            ->where($qb->expr()->like('c.' . $queryLabel, ':query'))
            ->setParameter('query', '%' . $query . '%');

        $substances = $qb->getQuery()->getResult();

        return $this->json($substances, 200, [], ['groups' => ['main']]);
    }

//    /**
//     * 持久化内容及所有字段数据
//     * @param FormInterface $form
//     * @param string $bundle 用于排序显示所有字段
//     * @param string $typeAlias 内容类型的别名，用于获取当前内容类型的所有字段
//     * @param string $contentClassName 内容Entity全类名，用于动态修改字段映射
//     * @param BaseContent $data
//     * @return mixed
//     * @throws ConnectionException
//     * @throws \Exception
//     */
//    protected function persistSubstance(FormInterface $form, string $bundle, string $typeAlias,
//                                        string $contentClassName, BaseContent $data = null)
//    {
//        //内容Entity object
//        $substance = $form->getData();
//
//        $this->entityManager->persist($substance);
//        $this->entityManager->flush();
//
//        //获取当前内容类型所有字段
//        $fields = $this->fieldConfigRepository
//            ->getBySortableGroupsQueryDesc(['bundle' => $bundle, 'typeAlias' => $typeAlias])->getResult();
//
//        //doctrine Event manager
//        $evm = $this->entityManager->getEventManager();
//
//        $conn = $this->entityManager->getConnection();
//
//        $conn->beginTransaction();
//        try {
//            /**@var FieldConfiguration $field * */
//            foreach ($fields as $field) {
//                //获取当前字段的所有表单数据
//                $fieldDataArray = $form->get($field->getFieldAlias())->getData();
//
//                if (!empty($fieldDataArray)) {
//                    //动态修改字段entity的mapping
//                    $dynamicChangeFieldMetadataListener = new DynamicChangeFieldMetadataListener($field, $contentClassName);
//                    $evm->addEventListener(Events::loadClassMetadata, $dynamicChangeFieldMetadataListener);
//
//                    /**@var BaseFieldItem $fieldItem * */
//                    foreach ($fieldDataArray as $index => $fieldItem) {
//                        //处理字段和内容Entity的关系
//                        $fieldItem->setEntity($substance);
//
//                        $fieldDBALUtils = new FieldDBALUtils($this->entityManager, $field);
//
//                        $fieldDBALUtils->persistFieldItem($fieldItem);
//
//                    }
//                    //移除doctrine监听器
//                    $evm->removeEventListener(Events::loadClassMetadata, $dynamicChangeFieldMetadataListener);
//                }
//            }
//
//            $conn->commit();
//        } catch (\Exception $exception) {
//            $conn->rollBack();
//            //如果原始内容id值为null，则为新建内容需要回滚删除
//            if (null == $data) {
//                $this->entityManager->remove($substance);
//                $this->entityManager->flush();
//            } else {
//                //如果原始内容id值不为null，则为编辑内容需要回滚到原始内容
//                $this->entityManager->persist($data);
//                $this->entityManager->flush();
//            }
//            throw $exception;
//        }
//
//        return $substance;
//    }
//
//
//    /**
//     * 删除内容及其字段数据
//     *
//     * @param string $bundle
//     * @param string $typeAlias
//     * @param BaseContent $data
//     * @throws ConnectionException
//     * @throws \Exception
//     */
//    protected function deleteSubstance(string $bundle, string $typeAlias, BaseContent $data)
//    {
//        //获取当前内容类型所有字段
//        $fields = $this->fieldConfigRepository
//            ->getBySortableGroupsQueryDesc(['bundle' => $bundle, 'typeAlias' => $typeAlias])
//            ->getResult();
//
//        $conn = $this->entityManager->getConnection();
//
//        $conn->beginTransaction();
//        try {
//            /**@var FieldConfiguration $field * */
//            foreach ($fields as $field) {
//                /**@var FieldInterface $fieldService * */
//                $fieldService = $this->container->get('teebb.core.field.' . $field->getFieldType());
//
//                $fieldDBALUtils = new FieldDBALUtils($this->entityManager, $field);
//
//                $fieldItems = $fieldService->getFieldEntityData($data, $field, get_class($data));
//
//                foreach ($fieldItems as $fieldItem) {
//                    $fieldDBALUtils->deleteFieldItem($fieldItem);
//                }
//            }
//            $conn->commit();
//        } catch (\Exception $e) {
//            $conn->rollBack();
//            throw $e;
//        }
//
//        $this->entityManager->remove($data);
//        $this->entityManager->flush();
//    }

    /**
     * 获取EntityType Service
     *
     * @param Request $request
     * @return EntityTypeInterface
     */
    protected function getEntityTypeService(Request $request): EntityTypeInterface
    {
        $entityTypeServiceId = $request->get('entity_type_service');
        if (null == $entityTypeServiceId) {
            throw new \RuntimeException(sprintf('The route "%s" config must define "entity_type_service" key.', $request->attributes->get('_route')));
        }

        return $this->container->get($entityTypeServiceId);
    }

}